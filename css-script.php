<style type="text/css">
    textarea { width: 100%; height: 100px;}
    .frm label {
        float: left;
        width: 150px;
    }

    .frm select {width: 140px;}

    .spacer-05 { clear: left; display: block; height: 5px; }
    .spacer-10 { clear: left; display: block; height: 10px; }

    .selpage{float: none !important; margin-left: 5px;}

    .logo { height: 40px; vertical-align: middle;}

    .help {cursor: help; position: relative; left: 3px;}

    .posts-container { max-height: 300px; overflow-x: hidden; overflow-y: auto; padding-left: 10px; width: 480px; border: 1px solid #aaa; }
</style>

<script type="text/javascript">
    function is_int(value) { 
        if ((parseFloat(value) == parseInt(value)) && !isNaN(value)) {
            return true;
        } else { 
            return false;
        } 
    }
    
    function validate_range_number(value, min, max){
        if(is_int(value)){
            if((parseInt(value) >= parseInt(min)) && (parseInt(value) <= parseInt(max))){
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function validateVideoStirEditForm()
    {
        var error = '', id, min, max;
        
        id = 'width', min = 0, max = 3000;
        if (!validate_range_number(document.getElementById(id).value, min, max)) {
            error += "- The " + id + " field must contain a \n number between " + min + " and " + max + ". \n";
        }
        
        id = 'height';
        if (!validate_range_number(document.getElementById(id).value, min, max)) {
            error += "- The " + id + " field must contain a \n number between " + min + " and " + max + ". \n";
        }
        
        id = 'val1', min = -3000, max = 3000;
        if (!validate_range_number(document.getElementById(id).value, min, max)) {
            error += "- The top or bottom field must contain a \n number between " + min + " and " + max + ". \n";
        }
        
        id = 'val2', min = -3000, max = 3000;
        if (!validate_range_number(document.getElementById(id).value, min, max)) {
            error += "- The left or right field must contain a \n number between " + min + " and " + max + ". \n";
        }
        
        id = 'url'
        if (parseInt(document.getElementById(id).value.length) <= 10) {
            error += "- The URL field must contain \n caraters and length greater than 10. \n";
        }
        
        id = 'rotation', min = 0, max = 360;
        if (document.getElementById(id).value != '') {
            if (!validate_range_number(document.getElementById(id).value, min, max)) {
                error += "- The rotation field must contain a \n number between " + min + " and " + max + ". \n";
            }
        }
        
        id = 'zoom', min = 0, max = 200;
        if (document.getElementById(id).value != '') {
            if (!validate_range_number(document.getElementById(id).value, min, max)) {
                error += "- The zoom field must contain a \n number between " + min + " and " + max + ". \n";
            }
        }
        
        id = 'playback-delay', min = 0, max = 200;
        if (document.getElementById(id).value != '') {
            if (!validate_range_number(document.getElementById(id).value, min, max)) {
                error += "- The delay field must contain a \n number between " + min + " and " + max + ". \n";
            }
        }
        
        id = 'auto-play-limit', min = 0, max = 10000;
        if (document.getElementById(id).value != '') {
            if (!validate_range_number(document.getElementById(id).value, min, max)) {
                error += "- The autoplay limit field must contain a \n number between " + min + " and " + max + ". \n";
            }
        }
        
        id = 'freeze', min = 1, max = 10000;
        if (document.getElementById(id).value != '') {
            if (!validate_range_number(document.getElementById(id).value, min, max)) {
                error += "- The freeze field must contain a \n number between " + min + " and " + max + ". \n";
            }
        }
        
        if (error != '') {
            alert(error);
            return false;
        } else {
            return true;
        }
    }

</script>
