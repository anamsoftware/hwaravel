<script src="assets\libs\dropify\dropify.min.js"></script>

<script type="text/javascript">
    $(".dropify").dropify({
        messages: {
            default: "Drag and drop a file here or click",
            replace: "Drag and drop or click to replace",
            remove: "Remove",
            error: "Ooops, something wrong appended."
        },
        error: {
            fileSize: "The file size is too big (1M max).",
        },
    });
</script>