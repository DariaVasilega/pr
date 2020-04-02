$('#create_user_avatar,#media_file').change( function(event) {
    let tempSrc = URL.createObjectURL(event.target.files[0]);
    const extensions = ['jpeg','jpg','png','jpeg 200','jp2'];
    let error=1;
    for (let item of extensions){

        if(event.target.files[0].type==='image/'+ item){
            $("#img").fadeIn("fast").attr('src',tempSrc);
            error=0;
            break;
        }
    }
    if(error === 0){
        $('#success').attr('hidden',false);
        setInterval(()=>{
            $('#success').attr('hidden',true);
        }, 3000);
    }else{
        $('#error').attr('hidden',false);
        $("#img").fadeIn("fast").attr('src','https://image.flaticon.com/icons/png/512/126/126477.png');
        setInterval(()=>{
            $('#error').attr('hidden',true)
        },3500);
    }
});
