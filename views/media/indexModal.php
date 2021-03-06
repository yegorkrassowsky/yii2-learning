<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\models\MediaCategory;

?>

<?php Pjax::begin([
		'id' => 'mediaGridPjax',
		'enablePushState' => false,
		'timeout' => 3000,
	]); ?>
<?= GridView::widget([
		'id' => 'mediaGrid',
        'dataProvider' => $dataProvider,
	    'filterModel' => $searchModel,
		'columns' => [
			[
				'attribute' => 'preview',
				'label' => 'Превью',
				'format' => 'raw',
				'value' => function($model){ return Html::a(Html::img($model->getMediaThumbnailURI('100x100')), Url::to(['media/update', 'id' => $model->id]), ['target' => '_blank', 'data' => [ 'pjax' => 0]]);}
			],
			[
				'attribute' => 'file_name',
				'format' => 'raw',
				'value' => function($model){ return Html::a($model->file_name, Url::to(['media/update', 'id' => $model->id]), ['target' => '_blank', 'data' => ['pjax' => 0]]);},
			],
            [
	            'attribute' => 'category',
	            'label' => 'Категория',
	            'value' => 'category.title',
	            'filter' => MediaCategory::itemsTree()
            ],
			[
				'class' => 'yii\grid\ActionColumn',
				'template' => '{select} {delete-modal}',
				'buttons' => [
					'select' => function($url, $model, $key){ return Html::a(Html::tag('span', '', ['class' => "glyphicon glyphicon-plus"]), $model->getMediaURI(), ['onclick' => 'mediaSelectModal(this)', 'data' => ['pjax' => 0]]);},
					'delete-modal' => function($url, $model, $key){return Html::a(Html::tag('span', '', ['class' => "glyphicon glyphicon-trash"]), $url, ['class' => 'mediaDelete', 'title' => 'delete', 'aria-label' => 'delete', 'data' => ['pjax' => 'mediaGridPjax', 'method' => 'post', 'confirm' => 'Are you sure you want to delete this item?']]);},
				]
			],
		],
    ]) ?>
<?php Pjax::end(); ?>

