<?php

if (!function_exists('jsonExtract')) {
    /**
     * @param string $column
     * @param string $path
     * @return string
     */
    function jsonExtract(string $column, string $path): string
    {
        // Convert non-JSON value column to the right JSON format
        $jsonObjColumn = 'JSON_OBJECT(\'' . $path . '\', ' . $column . ')';
        $isValidJson = 'JSON_VALID(' . $column . ')';
        $column = 'IF(' . $isValidJson . ', ' . $column . ', ' . $jsonObjColumn . ')';

        $path = (starts_with($path, '[')) ? '$' . $path : '$.' . $path;

        // Apply WHERE clause using MySQL JSON methods
        // $jsonColumn = $column . '->>"' . $path . '"'; // MySQL 5.7.13
        $jsonColumn = 'JSON_UNQUOTE(JSON_EXTRACT(' . $column . ', \'' . $path . '\'))';

        return $jsonColumn;
    }
}
