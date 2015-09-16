<?php

namespace K3roulas\EmailWatcher\LastUidPersist;

/**
 * Class LastUidPersistFile
 *
 * @package K3roulas\EmailWatcher\LastUidPersist
 */
class LastUidPersistFile implements LastUidPersistInterface
{
    /** @var string */
    private $filename;

    /**
     * @param string $filename
     */
    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @return string|null
     */
    public function getLastUid()
    {
        $lastId = null;

        if (file_exists($this->filename)) {

            if ($json = file_get_contents($this->filename)) {
                $storage = json_decode($json);
                $lastId = $storage->lastUid;
            }

        }

        return $lastId;
    }


    /**
     * @param int $lastId
     *
     * @throws \Exception
     */
    public function setLastUid($lastId)
    {
        $storage = array(
            'lastUid' => $lastId
        );
        $success = file_put_contents($this->filename, json_encode($storage));
        if (false === $success) {
            throw new \Exception(sprintf('Cannot store last process uid in file %s', $this->filename));
        }

    }


} 