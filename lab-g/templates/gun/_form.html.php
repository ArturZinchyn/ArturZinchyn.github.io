<?php
/** @var $gun ?\App\Model\Gun */
?>

<div class="form-group">
    <label for="name">Name</label>
    <input type="text" id="name" name="gun[name]" value="<?= $gun ? $gun->getName() : '' ?>">
</div>

<div class="form-group">
    <label for="caliber">Caliber</label>
    <input type="text" id="caliber" name="gun[caliber]" value="<?= $gun ? $gun->getCaliber() : '' ?>">
</div>

<div class="form-group">
    <label for="magazine_capacity">Magazine Capacity</label>
    <input type="number" id="magazine_capacity" name="gun[magazine_capacity]" value="<?= $gun ? $gun->getMagazineCapacity() : '' ?>">
</div>

<div class="form-group">
    <label></label>
    <input type="submit" value="Submit">
</div>