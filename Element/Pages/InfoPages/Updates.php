<div class="UI-Block Info-Block UI-B_FIRST">
    <div class="UI-Title">История обновлений</div>
    <div class="UI-Tabs">
        <button onclick="GetUpdates('Release')" class="Tab ActiveTab">Обновления</button>
        <div class="UI-PUSTOTA_W"></div>
        <button onclick="GetUpdates('Beta')" class="Tab">Бета-версии</button>
    </div>
    <div id="UPDATES_HISTORY"></div>
</div>
<script>
    GetUpdates('Release');
</script>