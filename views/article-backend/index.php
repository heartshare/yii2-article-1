<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use asinfotrack\yii2\toolbox\widgets\Button;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedActionColumn;
use asinfotrack\yii2\toolbox\widgets\grid\AdvancedDataColumn;
use asinfotrack\yii2\toolbox\widgets\grid\IdColumn;
use asinfotrack\yii2\article\Module;
use asinfotrack\yii2\article\helpers\ArticleCategoryHelper;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \asinfotrack\yii2\article\models\search\ArticleSearch */

$this->title = Yii::t('app', 'Articles');
$typeFilter = call_user_func([Module::getInstance()->classMap['articleModel'], 'typeFilter']);
?>

<div class="buttons">
	<?= Button::widget([
		'tagName'=>'a',
		'icon'=>'asterisk',
		'label'=>Yii::t('app', 'Create an article'),
		'options'=>[
			'href'=>Url::to(['article-backend/create']),
			'class'=>'btn btn-primary',
		],
	]) ?>
</div>

<?= GridView::widget([
	'dataProvider'=>$dataProvider,
	'filterModel'=>$searchModel,
	'columns'=>[
		[
			'class'=>IdColumn::class,
			'attribute'=>'id',
		],
		[
			'class'=>AdvancedDataColumn::class,
			'attribute'=>'canonical',
			'format'=>'html',
			'columnWidth'=>20,
			'value'=>function ($model, $key, $index, $column) {
				return Html::tag('code', $model->canonical);
			},
		],
		'title',
		[
			'attribute'=>'title_internal',
			'columnWidth'=>25,
		],
		[
			'class'=>AdvancedDataColumn::class,
			'attribute'=>'type',
			'columnWidth'=>10,
			'filter'=>$typeFilter,
			'value'=>function ($model, $key, $index, $column) use ($typeFilter) {
				return $typeFilter[$model->type];
			},
		],
		[
			'class'=>AdvancedActionColumn::class,
			'template'=> function($model) {
				$buttons = ['{view}'];
				if (ArticleCategoryHelper::checkEditCategoryPermissions($model)) {
					$buttons[] = '{update}';
					$buttons[] = '{delete}';
				}
				return implode(' ', $buttons);
			}
		],
	],
]); ?>
