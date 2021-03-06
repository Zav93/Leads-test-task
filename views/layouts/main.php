<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta charset="<?= Yii::$app->charset ?>">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
	<?php
	NavBar::begin([
		'brandLabel' => Yii::$app->name,
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-default navbar-fixed-top',
		],
	]);
	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => [ Yii::$app->user->isGuest
				? ([
					'label' => 'Login',
					'url' => ['/site/login']
				])
				: (
					'<li style="margin-top: 15px;">' . "Balance <span id='nav_balance'>" . Yii::$app->user->identity->balance . '</span></li>' .
					'<li>'
						. Html::beginForm(['/site/logout'], 'post')
							. Html::submitButton(
								'Logout (' . Yii::$app->user->identity->first_name . ')',
								['class' => 'btn btn-link logout']
							)
						. Html::endForm()
					. '</li>'
				)
		],
	]);
	NavBar::end();
	?>

	<div class="container">
		<?= Breadcrumbs::widget([
			'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
		]) ?>
		<?= Alert::widget() ?>
		<?= $content ?>
	</div>
</div>

<footer class="footer">
	<div class="container">
		<p class="pull-left">&copy; My Company <?= date('Y') ?></p>

		<p class="pull-right"><?= Yii::powered() ?></p>
	</div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
