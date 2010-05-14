<?php

class afJoinUtil {
    public static function chooseJoins($criteria, $class,
            $selectedColumns, $referencedTables) {
        list($numFCols, $selectedFCols) = self::getNumAndSelectedForeignCols(
            $class, $selectedColumns, $referencedTables);
        if(count($selectedFCols) === 0) {
            return 'doSelect';
        }

        if(count($selectedFCols) === $numFCols) {
            return 'doSelectJoinAll';
        }

        if(count($selectedFCols) === 1) {
            $col = $selectedFCols[0];
            $dbName = $col->getTable()->getDatabaseMap()->getName();
            $relatedTable = $col->getRelatedTableName();
            $relatedPhpName = afMetaDb::getPhpName($dbName, $relatedTable);
            return 'doSelectJoin'.$relatedPhpName;
        }

        //TODO: use also doSelectJoinAllExcept...

        foreach($selectedFCols as $fcol) {
            $criteria->addJoin($fcol->getFullyQualifiedName(),
                $fcol->getRelatedTableName().'.'.$fcol->getRelatedColumnName(),
                Criteria::LEFT_JOIN);
        }
        return 'doSelect';
    }

    private static function getTableMap($class) {
        $peer = constant($class.'::PEER');
        return call_user_func(array($peer, 'getTableMap'));
    }

    private static function getNumAndSelectedForeignCols($class,
            $selectedColumns, $refTables) {
        $tableMap = self::getTableMap($class);
        $numFCols = 0;
        $selectedFCols = array();
        foreach($tableMap->getColumns() as $col) {
            $relatedTable = $col->getRelatedTableName();
            if($relatedTable) {
                $numFCols += 1;
                if(in_array($relatedTable, $refTables)) {
                    $selectedFCols[] = $col;
                } else if(in_array(strtolower($col->getName()),
                        $selectedColumns)){
                    $selectedFCols[] = $col;
                }
            }
        }
        return array($numFCols, $selectedFCols);
    }
}

