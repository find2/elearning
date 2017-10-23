<?php
    session_start();
    require 'lib.php';
    $username=$_SESSION['e_username'];
    $object = new CRUD();
    $data=$object->Enroll_Modal(); 
    $data.=$object->Header_Enroll();
    $codes = $object->Data_Enroll_Table($username);
    if(count($codes)>0){
        $number=0;
        foreach ($codes as $code) {
            $number++;
            $data.= $object->Data_Enroll($number, $code['code'], $code['date_created'], $code['id'], $code['class_name']);
        }
    }
    else{
        $data.='
        <tr>
            <td colspan="3">Record Not Found. </td>
        </tr>
        ';
    }
    $data.=' </tbody>
                </table>
            </div></div>';
    echo $data;
?>