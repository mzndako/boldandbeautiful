<div class="mail-header" style="padding-bottom: 27px ;">
    <!-- title -->
    <h3 class="mail-title">
        <?php echo get_phrase('submit_lesson_note/plan'); ?>
    </h3>
</div>

<div class="mail-compose">

    <?php echo form_open(base_url() . 'index.php?admin/lesson/send_new/'.$current_term.(isset($edit_id)?"/$edit_id":""), array('class' => 'form', 'enctype' => 'multipart/form-data')); ?>



    <div class="form-group">
        <input type="text" style="padding: 3px; border: 1px solid grey;" value="<?php echo $title; ?>" class="form-control" name="title" placeholder="TITLE" />


    </div>

    <div id="colors_tools" style="display: none;">
    <div class='colors_tools' >
        <a href="#colors_sketch" data-color="white">Eraser</a>
        <a href='#colors_sketch' data-download='png' style='float: right; width: 150px;'>Insert Drawing</a>
        <a href='#colors_tools' onclick="$('#colors_tools').hide(500)" data-download='png' style='float: right; width: 100px;'>Close</a>
    </div>
    <canvas id='colors_sketch' width='800' height='300'></canvas>
    </div>
    <a href="#colors_sketch" class="btn  btn-warning" onclick="$('#colors_tools').show(500)">Show Drawing Pad</a>


<div id="editor"></div>
    <div class="compose-message-editor">
        <textarea row="20" id="ckeditor" class="form-control wysihtml5 ckeditor" data-stylesheet-url="assets/css/wysihtml5-color.css"
            name="message" placeholder="<?php echo get_phrase('write_your_message'); ?>"
            id="sample_wysiwyg"><?php echo $message; ?></textarea>
    </div>

    <hr>

    <button type="submit" class="btn btn-success btn-icon pull-right">
        <?php echo get_phrase('save'); ?>
        <i class="entypo-mail"></i>

    </button>
</form>
<style>
    code { background: #cff; }

    .colors_tools { margin-bottom: 10px; }
    .colors_tools a {
        border: 1px solid black; height: 30px; line-height: 30px; padding: 0 10px; vertical-align: middle; text-align: center; text-decoration: none; display: inline-block; color: black; font-weight: bold;
    }
</style>

</div>
<script type="text/javascript">

    $(document).ready(function() {

       $(function() {
            $.each(['#f00', '#ff0', '#0f0', '#0ff', '#00f', '#f0f', '#000', '#fff'], function() {
                $('.colors_tools').append("<a href='#colors_sketch' data-color='" + this + "' style='width: 10px; background: " + this + ";'></a> ");
            });
            $.each([3, 5, 10, 15, 30, 60], function() {
                $('.colors_tools').append("<a href='#colors_sketch' data-size='" + this + "' style='background: #ccc'>" + this + "</a> ");
            });
            $('#colors_sketch').sketch();
        });


    });
</script>