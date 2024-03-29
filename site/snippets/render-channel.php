<?php 

/**
 * @param $channel 	— Kirby page
 * @param $index 		— Channel number (1-based)
 */


$template = $channel->template()->name();

$onChannelClick = "";
if ($template == "channel") {
	
	if ($channel->videoList()->toPages()->count() > 0) {

		$validItems = $channel->videoList()->toPages()->filter(function ($p) {
			return $p->vimeo()->isNotEmpty();
		});
		
		// $firstVideo = $channel->videoList()->toPages()->first();
		$firstVideo = $validItems->first();

		$onChannelClick = "onclick=\"a.changeVideo('". $firstVideo->vimeo()->value() ."', '". $firstVideo->title() ."', '". $index ."');\"";
	}
} elseif ($template == "channel-stream") {
	// $onChannelClick = "onclick=\"alert('". $index ."');\"";
	


	// ------------------ HANDLE IF LIST EMPTY !!!!!!!!!!!!!!!!!
	$vimeoId = $channel->streamId()->value();
	$firstVideo = $channel->getFirstDraftVideo();
	$title = ($firstVideo !== null) ? $firstVideo->title()->value() : "Live session";
	$onChannelClick = "onclick=\"a.changeVideo('". $vimeoId ."', '". $title ."', '". $index ."');\"";
}
?>

<div id="channel-<?= $index ?>" class="column" data-channel-number="">
	<div class="rotated-bar">
		<a class="title font-bit-xl color-white" <?= $onChannelClick ?>>
			<?= $index ?>&nbsp;&nbsp;&nbsp;<?= $channel->title() ?>
		</a>
		<a class="arrow-wrapper font-bit-xl color-purple" onclick="a.toggleSchedule('channel-<?= $index ?>');">
			<span class="schedule-text font-sans-s mr-4 d-none d-lg-block"></span>
			<span class="arrow"></span>
		</a>
	</div>
	<div class="schedule">
		
		<?php if ($template == "channel-stream"): ?>
		
			<div class="schedule-title">
				<h2 class="font-bit-xl color-white">Upcoming live sessions</h2>
				<?php if ($channel->getFirstDraftVideo() === null): ?>
					<p class="font-sans-m color-white mt-5">No items.</p>
				<?php endif ?>
			</div>

			<?php foreach ($channel->schedule()->toStructure() as $item): ?>

				<?php 
				if ($item->isActive()->toBool() === false) {
					continue;
				}
				$vId = $item->videoItem()->yaml()[0];
				$v = kirby()->page($vId);
				$descId = "desc-". $vId;
				$descId = "ch-$index-" . str_replace("/", "-", $descId);
				?>

				<div class="schedule-item stream">
					<div class="left">
						<p class="font-sans-s mb-2 pb-1">
							<span class="color-orange">
								<?= $item->itemdate()->toDate('M j') ." ". 
										$item->itemtimefrom()->toDate('G.i') ."-". 
										$item->itemtimeto()->toDate('G.i')
								?> GMT
							</span>
							&nbsp;&nbsp;&nbsp;
							<span class="color-purple"><?= $v->programType()->upper() ?></span>
						</p>
						<span class="program-title font-sans-m font-medium color-white" onclick=""><?= $v->title() ?></span>
						<div class="description font-sans-s color-white" id="<?= $descId ?>"><?= $v->description()->kt() ?></div>
					</div>
					<div class="right">
						<?php if ($v->description()->isNotEmpty()): ?>
							<p class="font-sans-s color-purple mb-2 pb-1"><a data-toggle-desc="<?= $descId ?>"></a></p>
						<?php endif ?>
					</div>
				</div>
			<?php endforeach ?>
		
		<?php elseif ($template == "channel"): ?>

			<?php foreach ($channel->videoList()->toPages() as $v): ?>

				<?php 
				$descId = "desc-". $v->id();
				$descId = "ch-$index-" . str_replace("/", "-", $descId);
				?>

				<div class="schedule-item">
					<div class="left">
						<p class="font-sans-s color-purple mb-2 pb-1"><?= $v->programType()->upper() ?></p>

						<!--  
						<a class="font-sans-m color-white clickable-title"
							onclick="a.changeVideo('<?= $v->vimeo()->value() ?>', '<?=$v->title() ?>', '<?= $index ?>', true);"
						><?= $v->title() ?></a>
						-->
						
						<p class="program-title font-sans-m font-medium color-white"><?= $v->title() ?></p>
						<div class="description font-sans-s color-white" id="<?= $descId ?>"><?= $v->description()->kt() ?></div>
						<p class="mt-2 pt-2"><a class="button"
									onclick="a.changeVideo('<?= $v->vimeo()->value() ?>', '<?=$v->title() ?>', '<?= $index ?>', true);">
									WATCH NOW</a></p>

					</div>
					<div class="right">
						<?php if ($v->description()->isNotEmpty()): ?>
							<p class="font-sans-s color-purple mb-2 pb-1"><a data-toggle-desc="<?= $descId ?>"></a></p>
						<?php endif ?>
					</div>
				</div>
			<?php endforeach ?>
		
		<?php endif ?>

	</div>
</div>








