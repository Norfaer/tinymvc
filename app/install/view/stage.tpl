<h2><?=$header_1?></h2>
<div class="well well-lg">
    <div class="row">
        <div class="col-sm-9 col-xs-12">
            <table class="table">
                <caption>1.<?=$strings[0]?></caption>
                <thead>
                    <tr>
                        <?foreach($php_settings_header as $header):?>
                        <th><?=$header?></th>
                        <?/foreach?>
                    </tr>
                </thead>
                <tbody>
                    <?foreach($php_settings as $row):?>
                    <tr>
                        <?foreach($row as $val):?>
                        <td><?=$val?></td>
                        <?/foreach?>
                    </tr>
                    <?/foreach?>
                </tbody>
            </table>           
        </div>
        <div class="col-sm-3 hidden-xs">
        </div>
    </div>
</div>