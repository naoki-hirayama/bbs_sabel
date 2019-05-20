<?php if ($paginator->count > $paginator->limit) : ?>
  <nav>
   <div class="pager">
    <p class="currentPage"><?php echo $paginator->viewer->getCurrent() ?>／<?php echo $paginator->viewer->getLast() ?></p>

    <ul>
      <if expr="$paginator->viewer->hasPrevious()">
        <li class="prev"><a href="<?php echo uri($paginator->uri) ?>?<?php echo $paginator->getUriQuery($paginator->viewer->getPrevious()) ?>">前へ</a></li>
      <else />
        <li class="prev"><span>前へ</span></li>
      </if>

      <if expr="$paginator->viewer->hasNext()">
        <li class="next"><a href="<?php echo uri($paginator->uri) ?>?<?php echo $paginator->getUriQuery($paginator->viewer->getNext()) ?>">次へ</a></li>
      <else />
        <li class="next"><span>次へ</span></li>
      </if>
    </ul>

   </div>
  </nav>
<?php endif ?>
