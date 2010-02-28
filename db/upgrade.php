<?php 

function xmldb_checklist_upgrade($oldversion=0) {

    global $CFG, $THEME, $db;

    $result = true;

    if ($result && $oldversion < 2010022500) {
        // Adjust (currently unused) 'teachermark' fields to be 0 when unmarked, not 2
        $sql = 'UPDATE '.$CFG->prefix.'checklist_check ';
        $sql .= 'SET teachermark=0 ';
        $sql .= 'WHERE teachermark=2';
        $result = execute_sql($sql);
    }

    if ($result && $oldversion < 2010022800) {
        // All checklists created before this point were 'student only' checklists
        // Update the default & previously created checklists to reflect this
        
        $sql = 'UPDATE '.$CFG->prefix.'checklist ';
        $sql .= 'SET teacheredit=0 ';
        $sql .= 'WHERE teacheredit=2';
        $result = execute_sql($sql);
        
        $table = new XMLDBTable('checklist');
        $field = new XMLDBField('teacheredit');
        $field->setAttributes(XMLDB_TYPE_INTEGER, '4', XMLDB_UNSIGNED, null, null, null, null, '0', null);
        $result = $result && change_field_type($table, $field);
    }
        
    return $result;

}

?>
