<?php 
$this->title = '分类管理';
 ?>
<a href="/catelist.xls" download="catelist" >下载示例</a>

<form method="post" action="/cate/file"  enctype="multipart/form-data" style="margin:30px">
	<input type="file" name="catelist" style="margin-bottom: 30px">
	<input type="submit" name="submit" value="Submit" class="btn btn-primary"/>
</form>

