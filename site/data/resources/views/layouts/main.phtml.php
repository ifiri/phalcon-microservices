<?= $this->tag->getDoctype() ?>
<html>
    <head>
        <?= $this->tag->getTitle() ?>

        <?php $this->assets->outputCss(); ?>
    </head>

    <body>
        <?= $this->getContent() ?> 

        <?php $this->assets->outputJs(); ?>
    </body>
</html>