<?php

/**
 * FormData.php
 * @author: Ali Güçlü <aliguclutr@gmail.com>
 * @date 29.08.2023 00:09
 */

namespace BMVC\Libs\Request;

class FormData
{
	public $inputs = [];
	public $files = [];
	private $content;

	public function __construct($content = null)
	{
		$this->content = $content ?: file_get_contents("php://input");
		$this->parseContent($this->content);
	}

	private function parseContent($content): void
	{
		$parts = $this->getParts($content);

		foreach ($parts as $part) {
			$this->processContent($part);
		}
	}

	private function getParts($content): array
	{
		$boundary = $this->getBoundary($content);

		if (is_null($boundary))
			return [];

		$parts = explode($boundary, $content);

		return array_filter($parts, function ($part): bool {
			return mb_strlen($part) > 0 && $part !== "--\r\n";
		});
	}

	private function getBoundary($content): ?string
	{
		$firstNewLinePosition = strpos($content, "\r\n");

		return $firstNewLinePosition ? substr($content, 0, $firstNewLinePosition) : null;
	}

	private function processContent($content): void
	{
		$content = ltrim($content, "\r\n");
		[$rawHeaders, $rawContent] = explode("\r\n\r\n", $content, 2);

		$headers = $this->parseHeaders($rawHeaders);

		if (isset($headers['content-disposition'])) {
			$this->parseContentDisposition($headers, $rawContent);
		}
	}

	private function parseHeaders($headers): array
	{
		$data = [];

		$headers = explode("\r\n", $headers);

		foreach ($headers as $header) {
			[$name, $value] = explode(':', $header);

			$name = strtolower($name);

			$data[$name] = ltrim($value, ' ');
		}

		return $data;
	}

	private function parseContentDisposition($headers, $content): void
	{
		$content = substr($content, 0, strlen($content) - 2);

		preg_match('/^form-data; *name="([^"]+)"(; *filename="([^"]+)")?/', $headers['content-disposition'], $matches);
		$fieldName = $matches[1];

		$fileName = $matches[3] ?? null;

		if (is_null($fileName)) {
			$input = $this->transformContent($fieldName, $content);

			$this->inputs = array_merge_recursive($this->inputs, $input);
		} else {
			$file = $this->storeFile($fileName, $headers['content-type'], $content);

			$file = $this->transformContent($fieldName, $file);

			$this->files = array_merge_recursive($this->files, $file);
		}
	}

	private function transformContent($name, $value): array
	{
		parse_str($name, $parsedName);

		$transform = function ($array, $value) use (&$transform) {
			foreach ($array as &$val) {
				$val = is_array($val) ? $transform($val, $value) : $value;
			}

			return $array;
		};

		return $transform($parsedName, $value);
	}

	private function storeFile($name, $type, $content): array
	{
		$tempDirectory = sys_get_temp_dir();
		$tempName = tempnam($tempDirectory, 'mira');
		file_put_contents($tempName, $content);

		register_shutdown_function(function () use ($tempName) {
			if (file_exists($tempName)) {
				unlink($tempName);
			}
		});

		return [
			'name' => $name,
			'type' => $type,
			'tmp_name' => $tempName,
			'error' => 0,
			'size' => filesize($tempName),
		];
	}
}