<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Form for editing HTML block instances.
 *
 * @package   block_leaderboard
 */

class block_leaderboard extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_leaderboard');
    }

    
    function get_content() {

        //global variable to access database
        global $DB;
        
        if ($this->content !== NULL) {
            return $this->content;
        }
        //Creating empty string to store our result.
        $userstring='';
        //getting data of the tables from the database.
        $grades= $DB->get_records('grade_grades');
        $users= $DB->get_records('user');
        $courses=$DB->get_records('course');
        $items=$DB->get_records('grade_items');
        $courseid=0;
        $itemid=0;
        $userid=0;
        $grade1=-1;
        $grade2=-1;
        $grade3=-1;
        $first=-1;
        $second=-1;
        $third=-1;


        foreach($courses as $course){

            if($course->category > 0){
                
                $courseid=$course->id;

                $userstring.='<b style="color:blue">'. $course->fullname . '</b><br>' ;
            }
            else
            {

                continue;
            }

            foreach($items as $item){

                
                if($item->courseid== $courseid){

                    
                    $itemid=$item->id;
                    break;

                }
            }
            foreach($grades as $grade){

           
                if($grade->itemid == $itemid ){

                        if($grade->finalgrade>$grade1){
                            $third=$second;
                            $grade3=$grade2;
                            $second=$first;
                            $grade2=$grade1;
                            $first=$grade->userid;
                            $grade1=$grade->finalgrade;
                        }else if($grade->finalgrade> $grade2){
                            $third=$second;
                            $grade3=$grade2;
                            $second=$grade->userid;
                            $grade2=$grade->finalgrade;
                        }else if($grade->finalgrade> $grade3){
                            $grade3=$grade->finalgrade;
                            $third=$grade->userid;
                        }

                    
                }  
            }
                  
                    foreach($users as $user){
                  
                    if($user->id == $first){
                        $userstring.='<br>' . '<b style="color:darkgreen;">1.' . ' ' . $user->lastname . ' ' . $grade1  .'</b>' ;
                      }
                    }
                    foreach($users as $user){

                        
                        if($user->id == $second){

                            $userstring.='<br><b style="color:darkmagenta;">2.' . ' ' . $user->lastname . ' ' . $grade2 . '</b>' ;

                        }
                    }
                    foreach($users as $user){

                       
                        if($user->id == $third){

                            $userstring.='<br><b style="color:darkred;">3.'  . ' ' . $user->lastname . ' ' . $grade3 . '</b>';

                        }
                    }
                
            
        }

       $this->content = new stdClass;
       
        $this->content->text = $userstring;
        return $this->content;

        
    }

   
}
