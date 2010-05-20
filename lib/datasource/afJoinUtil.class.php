<?php

class afJoinUtil {
    public static function chooseJoins($criteria, $class,
            $selectedColumns, $referencedTables) {
        list($duplication, $selectedFCols, $excludedFCols) =
            self::getForeignColsSelection(
                $class, $selectedColumns, $referencedTables);
        if(count($selectedFCols) === 0) {
            return 'doSelect';
        }

        if(!$duplication && count($excludedFCols) === 0) {
            return 'doSelectJoinAll';
        }

        if(count($selectedFCols) === 1) {
            $col = $selectedFCols[0];
            return 'doSelectJoin'.afMetaDb::getRelatedAffix($col);
        }

        if(!$duplication && count($excludedFCols) === 1) {
            $col = $excludedFCols[0];
            return 'doSelectJoinAllExcept'.afMetaDb::getRelatedAffix($col);
        }

        foreach($selectedFCols as $fcol) {
            $criteria->addJoin($fcol->getFullyQualifiedName(),
                $fcol->getRelatedTableName().'.'.$fcol->getRelatedColumnName(),
                Criteria::LEFT_JOIN);
        }
        return 'doSelect';
    }

    private static function getForeignColsSelection($class,
            $selectedColumns, $refTables) {
        $tableMap = afMetaDb::getTableMap($class);
        $fkCount = array();
        $selectedFCols = array();
        $excludedFCols = array();
        foreach($tableMap->getColumns() as $col) {
            $relatedTable = $col->getRelatedTableName();
            if($relatedTable) {
                if(in_array($relatedTable, $refTables) ||
                    in_array(strtolower($col->getName()), $selectedColumns)){

                    $selectedFCols[] = $col;
                    $fkCount[$relatedTable] = ArrayUtil::get($fkCount,
                        $relatedTable, 0) + 1;
                } else {
                    $excludedFCols[] = $col;
                }
            }
        }

        $duplication = $fkCount && max($fkCount) > 1;
        return array($duplication, $selectedFCols, $excludedFCols);
    }
}

