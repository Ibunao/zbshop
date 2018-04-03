<div class="form-group">
   <label class="col-sm-3 control-label" for="state">选择分类</label>
   <div class="col-sm-3">
     <select name="dealer_id" id="dealer_id" class="selectpicker show-tick form-control"  data-width="98%" data-first-option="false" title='请选择经销商(必选)' required data-live-search="true">
     	<?php foreach (Yii::$app->params['categories'] as $key => $value): ?>
        	<option value="<?=$key ;?>"><?=str_repeat('--', $value['level'] - 1).$value['name'] ;?></option>
     	<?php endforeach ?>
     </select>
   </div>
</div>