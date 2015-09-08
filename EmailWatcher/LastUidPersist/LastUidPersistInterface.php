<?php

namespace K3roulas\EmailWatcher\LastUidPersist;

/**
 * Interface LastUidPersistInterface
 *
 * @package K3roulas\EmailWatcher\LastUidPersist
 */
interface LastUidPersistInterface
{

    /**
     * @return mixed
     */
    public function getLastUid();

    /**
     * @param integer $uid
     *
     * @throws \Exception
     */
    public function setLastUid($uid);


} 