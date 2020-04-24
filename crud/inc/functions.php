<?php
define( 'DB_NAME', 'E:\\Workplace\\php\\crud\\data\\db.txt' );
function seed() {
    $data   =   array(
        array(
            'id'    => 1,
            'fname' => 'Kamal',
            'lname' => 'Ahmed',
            'roll'  => '11'
        ),
        array(
            'id'    => 2,
            'fname' => 'Jamal',
            'lname' => 'Ahmed',
            'roll'  => '12'
        ),
        array(
            'id'    => 3,
            'fname' => 'Ripon',
            'lname' => 'Ahmed',
            'roll'  => '9'
        ),
        array(
            'id'    => 4,
            'fname' => 'Nikhil',
            'lname' => 'Chandra',
            'roll'  => '8'
        ),
        array(
            'id'    => 5,
            'fname' => 'John',
            'lname' => 'Rozario',
            'roll'  => '7'
        ),
    );
    $serializedData = serialize( $data );
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}
function generateReport(){
    $serializedData = file_get_contents(DB_NAME);
    $students = $serializedData != '' ? unserialize($serializedData) : array();
    ?>
    <table>
        <tr>
            <th>Name</th>
            <th>Roll</th>
            <th width="25%">Action</th>
        </tr>
        <?php
        if(count($students)>0){
            foreach ($students as $student) {
        ?>
        <tr>
            <td><?php printf("%s %s", $student['fname'], $student['lname']);?></td>
            <td><?php printf("%s", $student['roll']);?></td>
            <td><?php printf('<a href="/?task=edit&id=%s">Edit</a> | <a class="delete" href="/?task=delete&id=%s">Delete</a>', $student['id'],  $student['id']);?></td>
        </tr>
        <?php
            }
        }else{
            ?>
            <tr>
                <td>No student data available</td>
            </tr>
            <?php
        }
        ?>
    </table>
    <?php
}

function addStudent($fname, $lname, $roll){
    $serializedData = file_get_contents(DB_NAME);
    $students = $serializedData != '' ? unserialize($serializedData) : array();
    $found = false;
    foreach($students as $_student){
        if($_student['roll'] == $roll){
            $found = true;
            break;
        }
    } 
    $newId = getNewId($students);
    if(!$found){
        $student =  array(
            'id'    => $newId,
            'fname' => $fname,
            'lname' => $lname,
            'roll'  => $roll
        );
        array_push($students, $student);
        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );
        return true;
    }else{
        return false;
    }
    
}
function getStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = $serializedData != '' ? unserialize($serializedData) : array();
    foreach($students as $student){
        if($student['id'] == $id){
            return $student;
        }
    }
    return false; 
}
function updateStudent($id,$fname, $lname, $roll){
    $found = false;
    $serializedData = file_get_contents(DB_NAME);
    $students = $serializedData != '' ? unserialize($serializedData) : array();
    foreach($students as $_student){
        if($id != $_student['id'] && $roll == $_student['roll']){
            $found = true;
        }
    }
    if(!$found){
        $students[$id-1]['fname'] = $fname;
        $students[$id-1]['lname'] = $lname;
        $students[$id-1]['roll'] = $roll;
        $serializedData = serialize( $students );
        file_put_contents( DB_NAME, $serializedData, LOCK_EX );
        return true;
    }
    return false;
}
function deleteStudent($id){
    $serializedData = file_get_contents(DB_NAME);
    $students = $serializedData != '' ? unserialize($serializedData) : array();
    foreach ($students as $offset => $student) {
        if($student['id'] == $id){
            unset($students[$offset]);
        }
    }

    $serializedData = serialize( $students );
    file_put_contents( DB_NAME, $serializedData, LOCK_EX );
}

function getNewId($students){
    $maxID = max(array_column($students, 'id'));
    return  $maxID + 1;
}