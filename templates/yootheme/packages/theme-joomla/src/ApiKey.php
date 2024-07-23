<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\Database\DatabaseDriver;

class ApiKey
{
    const ELEMENT = 'pkg_yootheme';

    protected DatabaseDriver $db;

    /**
     * Constructor.
     */
    public function __construct(DatabaseDriver $db)
    {
        $this->db = $db;
    }

    public function get(): string
    {
        $extension = $this->getExtension(static::ELEMENT);
        $updateSite = $this->getUpdateSite($extension->id ?? 0);

        parse_str($updateSite->extra_query ?? '', $params);

        return $params['key'] ?? '';
    }

    public function set($key): void
    {
        $extension = $this->getExtension(static::ELEMENT);
        $updateSite = $this->getUpdateSite($extension->id ?? 0);

        $query = fn($db, $query) => $query
            ->update('#__update_sites')
            ->set("{$db->qn('extra_query')} = {$db->q("key={$key}")}")
            ->where("{$db->qn('update_site_id')} = {$updateSite->update_site_id}");

        $this->createQuery($query)->execute();
    }

    protected function getExtension(
        $element,
        $type = 'package',
        $folder = '',
        $clientId = 0
    ): ?object {
        $query = fn($db, $query) => $query
            ->select('extension_id id')
            ->from($db->qn('#__extensions'))
            ->where("{$db->qn('type')} = {$db->q($type)}")
            ->where("{$db->qn('folder')} = {$db->q($folder)}")
            ->where("{$db->qn('element')} = {$db->q($element)}")
            ->where("{$db->qn('client_id')} = {$clientId}");

        return $this->createQuery($query)->loadObject();
    }

    protected function getUpdateSite($extensionId): ?object
    {
        $query = fn($db, $query) => $query
            ->select('s.*')
            ->from($db->qn('#__update_sites', 's'))
            ->innerJoin(
                sprintf(
                    '%s ON %s = %s',
                    $db->qn('#__update_sites_extensions', 'se'),
                    $db->qn('se.update_site_id'),
                    $db->qn('s.update_site_id'),
                ),
            )
            ->where("{$db->qn('se.extension_id')} = {$extensionId}");

        return $this->createQuery($query)->loadObject();
    }

    protected function createQuery(callable $callback): DatabaseDriver
    {
        return $this->db->setQuery($callback($this->db, $this->db->getQuery(true)));
    }
}
