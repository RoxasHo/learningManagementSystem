let editorInstance;

    ClassicEditor
        .create(document.querySelector('#editor'), {
            ckfinder: {
                uploadUrl: "{{ route('ckeditor.upload', ['_token' => csrf_token()]) }}",
            }
        })
        .then(editor => {
            editorInstance = editor;
        })
        .catch(error => {
            console.error(error);
        });

    function validateForm() {
        const content = editorInstance.getData().trim();

        if (content === '') {
            alert('Content cannot be blanked.');
            return false;
        }

        return true;
    }