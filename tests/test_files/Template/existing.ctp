<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Car[] $cars
 * @var \App\Model\Entity\Wheel $wheel
 */
?>
<div>
	<?php foreach ($cars as $car); ?>
	<?php echo h($wheel->id); ?>
</div>
