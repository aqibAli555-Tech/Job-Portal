<?php
$type = '';
$title = '';
$messageData = '<ul>';
?>

@foreach (session('flash_notification', collect())->toArray() as $message)
    @if (!str_contains($message['message'], 'Failed to authenticate'))
        @if ($message['level'] == 'danger')
            <?php
            $type = 'error';
            $title = 'Attention';
            $messageData .= '<li>' . $message['message'] . '</li>';
            ?>
        @else
            <?php
            $type = 'success';
            $previousUrl = url()->previous();
            
            // Keyword to search
            $keyword = 'register';
            
            // Check if the keyword exists in the previous URL
            if (strpos($previousUrl, $keyword) !== false) {
                $title = 'Welcome to Hungry For Jobs!';
            } else {
                $title = 'Great';
            }
            $messageData .= '<li>' . $message['message'] . '</li>';
            ?>
        @endif
    @endif
@endforeach
<?php $messageData .= '</ul>'; ?>
<?php $messageData = str_replace('"""', '', $messageData); ?>
<?php $messageData = str_replace('"', '', $messageData); ?>

@if (trim($messageData) != '<ul></ul>')
    <script>
        Swal.fire({
            html: '<?= $messageData ?>',
            icon: "<?= $type ?>",
            title: "<?= $title ?>",
            confirmButtonText: "<u>Ok</u>",
        });
    </script>
@endif
{{ session()->forget('flash_notification') }}
