<h1>DYNAMIC UPDATE SYSTEM</h1>

<?php
ini_set('max_execution_time',0);
 
$updated = false;
//Check for an update. We have a simple file that has a new release version on each line. (1.00, 1.02, 1.03, etc.)

$getVersions = file_get_contents('currentVersion') or die('ERROR');
$host = 'http://portal.nutemss.com';
//$host = 'http://localhost.com/myschool';

if ($getVersions != '')
{
    //If we managed to access that file, then lets break up those release versions into an array.
    echo '<p>CURRENT VERSION: v'.$getVersions.'</p>';
    echo '<p>Reading Current Releases List</p>';

    $aV = @file_get_contents($host.'/get_update.php?c='.$getVersions.'&nodownload');
    if($aV == "" || strlen($aV) > 6)
        die("Error: $aV");

            echo '<p>New Update Found: v'.$aV.'</p>';
           // echo '<a href="?doUpdate">Click here to Update</a>';
            $file_name = __DIR__.'/update/upgrade-'.$aV.'.zip';
           
            //Download The File If We Do Not Have It
        if (!is_file($file_name)) {
            echo '<p>Downloading New Update</p>';
            $newUpdate = file_get_contents($host . '/get_update.php?c=' . $getVersions . '&download');

            if (!is_dir(__DIR__ . '/update')) mkdir(__DIR__ . '/update');

            $dlHandler = fopen($file_name, 'w');
            if (!fwrite($dlHandler, $newUpdate)) {
                echo '<p>Could not save new update. Operation aborted.</p>';
                exit();
            }
            fclose($dlHandler);
            echo '<p>Update Downloaded And Saved</p>';
        } else echo '<p>Update already downloaded.</p>';

            if (isset($_GET['doUpdate'])) {
                //Open The File And Do Stuff
                $skipped = file_get_contents($host.'/get_update.php?c='.$getVersions.'&skipped');

                $skipped = explode(",",$skipped);
                $zipHandle = zip_open($file_name);

                echo '<ul>';
                while ($aF = zip_read($zipHandle) )
                {
                    $thisFileName = zip_entry_name($aF);
                    $thisFileDir = dirname($thisFileName);
                   
                    //Continue if its not a file
                    if ( substr($thisFileName,-1,1) == '/') continue;
                   
    
                    //Make the directory if we need to...
                    if ( !is_dir ( __DIR__.'/'.$thisFileDir ) )
                    {
                         mkdir ( __DIR__.'/'.$thisFileDir );
                         echo '<li>Created Directory '.$thisFileDir.'</li>';
                    }
                   
                    //Overwrite the file
                    if ( !is_dir(__DIR__.'/'.$thisFileName) ) {
                        echo '<li>'.$thisFileName.'...........';
                        $contents = zip_entry_read($aF, zip_entry_filesize($aF));
                        $contents = str_replace("rn", "n", $contents);
                        $updateThis = '';


                        $all = explode("/",$thisFileName);
                        $name = $all[count($all) - 1];
                        if(in_array($name,$skipped)){
                            echo "<b>SKIPPED</b>";
                            continue;
                        }
                        if ( $thisFileName == 'upgrade.php' )
                        {
                            $upgradeExec = fopen ('upgrade.php','w');
                            fwrite($upgradeExec, $contents);
                            fclose($upgradeExec);
                            include ('upgrade.php');
                            unlink('upgrade.php');
                            echo' EXECUTED</li>';
                        }
                        else
                        {
                            $updateThis = fopen(__DIR__.'/'.$thisFileName, 'w');
                            fwrite($updateThis, $contents);
                            fclose($updateThis);
                            unset($contents);
                            echo' UPDATED</li>';
                        }
                    }
                }
                echo '</ul>';
                $updated = TRUE;
            }else{
                print "<a href='?doUpdate'>UPDATE NOW</a>";
            }

    }
else echo '<p>Could not find latest realeases.</p>';
    
    if ($updated == true)
    {
        file_put_contents('currentVersion',$aV);
        echo '<p class="success">&raquo; Updated to v'.$aV.'</p>';
    }

    
