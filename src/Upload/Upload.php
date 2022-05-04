<?php

/**
 * Upload
 *
 * Mirarus BMVC
 * @package BMVC\Libs\Upload
 * @author  Ali Güçlü (Mirarus) <aliguclutr@gmail.com>
 * @link https://github.com/mirarus/bmvc-libs
 * @license http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version 0.0
 */

namespace BMVC\Libs\Upload;

class Upload
{

  /**
   * @var array
   */
  private $error = [
    'upload' => [
      0 => 'There is no error, the file uploaded with success',
      1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
      2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
      3 => 'The uploaded file was only partially uploaded',
      4 => 'No file was uploaded',
      6 => 'Missing a temporary folder',
      7 => 'Failed to write file to disk.',
      8 => 'A PHP extension stopped the file upload.'
    ],
    'system' => []
  ];

  /**
   * @var
   */
  private $path;

  /**
   * @var
   */
  private $file;

  /**
   * @var string
   */
  private $type = 'any';

  /**
   * @var int
   */
  private $size = 1024;

  /**
   * @var int
   */
  private $rand = 0;

  /**
   * @return array|false
   */
  public function upload()
  {
    if ($this->file) {
      if ($this->file['error'] == UPLOAD_ERR_OK) {

        $_name = pathinfo($this->file['name'], PATHINFO_FILENAME);
        $_name = $_name . ($this->rand() ? '-' . $this->rand() : null);
        $_ext  = strtolower(pathinfo($this->file['name'], PATHINFO_EXTENSION));
        $_file = basename($_name . '.' . $_ext);
        $_path = $this->path . DIRECTORY_SEPARATOR . $_file;
        $_type = explode('/', $this->file['type'])[0];

        if (!(is_dir($this->path) && opendir($this->path))) {
          mkdir($this->path, 0777, true);
        }

        if (($_type == $this->type) || $this->type == 'any') {
          if ($this->file['size'] <= $this->size * 1024) {
            if (move_uploaded_file($this->file['tmp_name'], $_path)) {

              return [
                'file' => $_path,
                'name' => $_file,
                'type' => $this->file['type'],
                'size' => $this->file['size']
              ];
            } else {
              $this->error['system'] = [
                'code' => 1005,
                'error' => 'Upload failed'
              ];
            }
          } else {
            $this->error['system'] = [
              'code' => 1004,
              'error' => 'File size large, sent file size ' . $this->file['size'] . ' Byte, acceptable file size ' . $this->size * 1024 . ' Byte'
            ];
          }
        } else {
          $this->error['system'] = [
            'code' => 1003,
            'error' => 'Invalid file type'
          ];
        }
      } else {
        $this->error['system'] = [
          'code' => 1002,
          'error' => $this->error['upload'][$this->file['error']] ?? 'Unknown error'
        ];
      }
    } else {
      $this->error['system'] = [
        'code' => 1001,
        'error' => 'File not found'
      ];
    }

    return false;
  }

  /**
   * @return int
   */
  private function rand(): int
  {
    return (int)rand(pow(10, $this->rand - 1), pow(10, $this->rand) - 1);
  }

  /**
   * @return array
   */
  public function getError(): array
  {
    return $this->error['system'];
  }

  /**
   * @param mixed $path
   */
  public function setPath($path): void
  {
    $this->path = $path;
  }

  /**
   * @param mixed $file
   */
  public function setFile($file): void
  {
    $this->file = $file;
  }

  /**
   * @param string $type
   */
  public function setType(string $type): void
  {
    $this->type = $type;
  }

  /**
   * @param int $size
   */
  public function setSize(int $size): void
  {
    $this->size = $size;
  }

  /**
   * @param int $rand
   */
  public function setRand(int $rand): void
  {
    $this->rand = $rand;
  }
}