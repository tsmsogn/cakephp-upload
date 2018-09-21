<?php
namespace Tsmsogn\CakephpUpload\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\Utility\Hash;

/**
 * Upload behavior
 */
class UploadBehavior extends Behavior
{
    const DEFAULT_CONFIG_KEY = 'default';
    const FIELD_CONFIG_KEY = 'fields';
    const STRATEGY_CONFIG_KEY = 'upload_strategy';

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        self::STRATEGY_CONFIG_KEY => \Tsmsogn\CakephpUpload\UploadInfo\DefaultUploadStrategy::class,
        self::DEFAULT_CONFIG_KEY => [
            'overwrite' => false,
            'directory' => WWW_ROOT . 'uploads',
            'filename' => null,
        ],
        self::FIELD_CONFIG_KEY => [],
    ];

    /**
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    /**
     * @param $data
     * @return array
     */
    public function upload($data)
    {
        $results = [];

        $defaultConfig = $this->getConfig(self::DEFAULT_CONFIG_KEY);
        $fields = $this->getConfig(self::FIELD_CONFIG_KEY);

        foreach ($fields as $field => $config) {
            if (!is_array($config)) {
                $field = $config;
                $config = [];
            }

            if (!array_key_exists($field, $data)) {
                continue;
            }

            $config = Hash::merge($defaultConfig, $config);

            $strategy = $this->_getUploadStrategy($config['directory'], $config['filename'], $config['overwrite']);

            $file = $this->_getFile($field, $strategy);
            $file->upload();

            $results[$field] = $file;
        }

        return $results;
    }

    /**
     * @param \Tsmsogn\CakephpUpload\UploadInfo\UploadAwareInterface $destinationStrategy
     * @return \Upload\File
     */
    protected function _getFile($field, \Tsmsogn\CakephpUpload\UploadInfo\UploadAwareInterface $destinationStrategy)
    {
        $storage = new \Upload\Storage\FileSystem($destinationStrategy->getDirectory(), $destinationStrategy->isOverwrite());
        $file = new \Upload\File($field, $storage);

        if ($destinationStrategy->getFilename() !== null) {
            $filename = $destinationStrategy->getFilename();
            if (is_callable($filename)) {
                $filename = call_user_func($filename, $file);
            }
            $file->setName($filename);
        }

        return $file;
    }

    /**
     * @param $directory
     * @param $filename
     * @param bool $overwrite
     * @return \Tsmsogn\CakephpUpload\UploadInfo\UploadAwareInterface
     */
    protected function _getUploadStrategy($directory, $filename, $overwrite = false)
    {
        return new $this->_config[self::STRATEGY_CONFIG_KEY]($directory, $filename, $overwrite);
    }
}
