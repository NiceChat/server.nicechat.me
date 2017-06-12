<div class="add">
  <div class="error"><?php echo validation_errors(); ?></div>
  <?php echo form_open('news/add');?>
    <label for="title">Title</label>
    <input type="input" name='title' /> <br>

    <label for="text">Text</label>
    <textarea name="text" id="" cols="30" rows="10"></textarea> <br>

    <input type="submit" name='submit' value='Create news item'>
  </form>
</div>