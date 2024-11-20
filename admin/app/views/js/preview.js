defaultFile = 'https://cdn-icons-png.flaticon.com/512/1570/1570791.png'
const file = document.getElementById('productImg');
const img = document.getElementById('preview');
    file.addEventListener('change', e =>{
        if(e.target.files[0]){
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }else{
            img.src = defaultFile;
        }

    }
);