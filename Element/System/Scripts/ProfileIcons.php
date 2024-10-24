<?php

$ProfileIcons = array (
    'VERIFY' => ['<svg class="Icon" iid="VERIFY" clicked="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m21.5609 10.7386-1.36-1.58001c-.26-.3-.47-.86-.47-1.26v-1.7c0-1.06-.87-1.93-1.93-1.93h-1.7c-.39 0-.96-.21-1.26-.47l-1.58-1.36c-.69-.59-1.82-.59-2.52 0l-1.57004 1.37c-.3.25-.87.46-1.26.46h-1.73c-1.06 0-1.93.87-1.93 1.93v1.71c0 .39-.21.95-.46 1.25l-1.35 1.59001c-.58.69-.58 1.81 0 2.5l1.35 1.59c.25.3.46.86.46 1.25v1.71c0 1.06.87 1.93 1.93 1.93h1.73c.39 0 .96.21 1.26.47l1.58004 1.36c.69.59 1.82.59 2.52 0l1.58-1.36c.3-.26.86-.47 1.26-.47h1.7c1.06 0 1.93-.87 1.93-1.93v-1.7c0-.39.21-.96.47-1.26l1.36-1.58c.58-.69.58-1.83-.01-2.52zm-5.4-.63-4.83 4.83c-.14.14-.33.22-.53.22s-.39-.08-.53-.22l-2.42004-2.42c-.29-.29-.29-.77 0-1.06s.77-.29 1.06 0l1.89004 1.89 4.3-4.30001c.29-.29.77-.29 1.06 0s.29.77 0 1.06001z"/></svg>', 'Это подтверждённый аккаунт'],
    'GOLD' => ['<svg class="Icon" iid="GOLD" clicked="0" viewBox="0 0 12.7 12.7" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg"><defs><linearGradient id="bbb"><stop offset="0" stop-color="#fab31e"/><stop offset="1" stop-color="#ffd479"/></linearGradient><linearGradient id="aaa"><stop offset="0" stop-color="#fab31e"/><stop offset=".744" stop-color="#ffd479"/></linearGradient><linearGradient xlink:href="#aaa" id="ccc" x1=".292" y1="6.362" x2="12.498" y2="6.362" gradientUnits="userSpaceOnUse" gradientTransform="matrix(.93282 0 0 .93282 .344 .4)"/><linearGradient xlink:href="#bbb" id="ddd" x1=".292" y1="6.362" x2="12.498" y2="6.362" gradientUnits="userSpaceOnUse" gradientTransform="matrix(.93282 0 0 .93282 .344 .4)"/></defs><path d="M7.296.694C7.106.646 5.043 4.02 4.898 4.078 4.752 4.137.927 3.16.824 3.327.72 3.494 3.29 6.497 3.3 6.65c.01.155-2.105 3.496-1.98 3.645.125.15 3.781-1.37 3.93-1.333.15.037 2.677 3.086 2.857 3.012.18-.073-.135-4.017-.055-4.148.08-.132 3.76-1.593 3.746-1.788-.014-.195-3.86-1.114-3.96-1.233-.1-.119-.353-4.065-.542-4.112z" fill="url(#ccc)" stroke="url(#ddd)" stroke-width="1.148" stroke-linejoin="round" paint-order="stroke fill markers"/></svg>', 'Этот аккаунт имеет активную подписку Gold'],
    'AN' => ['<img class="Icon" iid="AN" clicked="0" src="/System/Images/Profile_Icons/AN_Logo.png">', 'Владелец хостинга "AltNodes"'],
    'FAKE' => ['<img class="Icon" iid="FAKE" clicked="0" src="/System/Images/Profile_Icons/Fake.svg">', 'Этот аккаунт пытается выдавать себя за другое лицо, или же распространяет клевету']
);

if (isset($_GET['F']) && $_GET['F'] == 'IINFO') {

    $Icon = $_POST['IconID'];

    if (array_key_exists($Icon, $ProfileIcons)) {
        $Info = $ProfileIcons[$Icon][1];
    }

    $Info = array (
        'Info' => $Info
    );

    header('Content-Type: application/json');
    echo json_encode($Info);
}

?>