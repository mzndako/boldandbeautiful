<?php 
    $transport_students = $this->db->get_where('student' , array('transport_id' => $param2))->result_array();
?>

<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td>#</td>
                    <td><?php echo get_phrase('name');?></td>
                    <td><?php echo get_phrase('email');?></td>
                    <td><?php echo get_phrase('phone');?></td>
                    <td><?php echo get_phrase('class');?></td>
                </tr>
            </thead>
            <tbody>
            <?php 
                $count = 1;
                foreach($transport_students as $row):
            ?>
                <tr>
                    <td><?php echo $count++;?></td>
                    <td><?php echo $row['name'];?></td>
                    <td><?php echo $row['email'];?></td>
                    <td><?php echo $row['phone'];?></td>
                    <td>
                        <?php echo $this->db->get_where('class' , array('class_id' => $row['class_id']))->row()->name;?>
                    </td>
                </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>