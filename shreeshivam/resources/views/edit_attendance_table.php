
<?php
    $attendance = json_decode($attendance,true);
    $size = sizeof($attendance['report']);
    $atten = $attendance['report']; 
    echo '<h4>'.$date.'</h4><br>'; ?>
    <table class="table table-bordered table-striped table-hover js-basic-example dataTable myemptable">
        <thead>
            <tr>
                <th>IN</th>
                <th>OUT</th>
            </tr>
        </thead>
        <tbody >

    <?php
    for($i=1;$i<=ceil($size/2);$i++)
    {
        if(!array_key_exists('IN-'.$i, $atten))
        {
            $atten['IN-'.$i]='';
        }
        if(!array_key_exists('OUT-'.$i, $atten))
        {
            $atten['OUT-'.$i]='';
        }
        ?>

        <tr>
            <td>
                <input type="text" class="form-control" name="in[<?php echo $i; ?>]" value="<?php echo $atten['IN-'.$i]; ?>">
            </td>
            <td>
                <input type="text" class="form-control" name="out[<?php echo $i; ?>]" value="<?php echo $atten['OUT-'.$i]; ?>">
            </td>
        </tr>
   <?php } ?>
        </tbody>
    </table> 
    <input type="hidden" name="size" value="<?php echo $size; ?>">
    <input type="hidden" name="emp_id" value="<?php echo $emp_id; ?>">
    <input type="hidden" name="date" value="<?php echo $date; ?>">