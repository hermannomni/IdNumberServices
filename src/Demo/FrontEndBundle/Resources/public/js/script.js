$(function() {
    $("#accordion").accordion({
        collapsible: true,
        active: false
    });
    $("#dateOfBirthGenerate").datepicker({
        yearRange: "c-120:c",
        dateFormat: "ymmdd",
        changeMonth: true,
        changeYear: true
    });
    $("#dateOfBirthCheck").datepicker({
        yearRange: "c-120:c",
        dateFormat: "ymmdd",
        changeMonth: true,
        changeYear: true
    });
});

var dialogBoxSettings = {
    modal: true,
    buttons: {
        Ok: function() {
          $( this ).dialog( "close" );
        }
    }
};

var FormSubmiter = function(url)
{
    this.url = url;
}

FormSubmiter.prototype.execute = function(form, successCallBack, completedCallback) {
    var method = form.attr("method");
    var data = form.serialize();
    var parentThis = this;
    $.ajax({
      type: method,
      url: parentThis.url,
      data: data,
      dataType: "JSON"
    }).done(function(response) {
        successCallBack(response);
        completedCallback();
    })
    .fail(function(response) {
        var errorMessage = "";
        var errorLevel = "";
        if (response.responseJSON && response.responseJSON.status){
            switch(response.responseJSON.status){
                case 400:
                    errorMessage = response.responseJSON.message;
                    errorLevel = "warning";
                break;
                case 500:
                    errorMessage = response.responseJSON.message;
                    errorLevel = "critical";
                break;
                default:
                    errorMessage = "An unknown error has occured, please try again later";
                    errorLevel = "critical";
                break;
            }
        } else {
            errorMessage = "An unknown error has occured, please try again later";
            errorLevel = "critical";
        }
        
        if (errorLevel == "warning"){
            $("#warningBoxMessage").html(errorMessage);
            $("#warningBox").dialog(dialogBoxSettings);
        } else {
            $("#errorBoxMessage").html(errorMessage);
            $("#errorBox").dialog(dialogBoxSettings);
        }
        completedCallback();
    });
};

$("#formGenerateIdNumber").submit(function(e){
    e.preventDefault();
    var formSubmiter = new FormSubmiter(generateIdNumberUrl);
    $("#divBtnGenerateId").hide(200, function(){
        $("#divBtnGenerateIdWaiting").show(200);
    });
    formSubmiter.execute($(this), function (response){
        $("#successBoxMessage").html(response.message + ": " + response.data.idNumber);
        $("#successBox").dialog(dialogBoxSettings);
    }, function (){
        $("#divBtnGenerateIdWaiting").hide(200, function(){
            $("#divBtnGenerateId").show(200);
        });
    });
});

$("#formCheckIdNumber").submit(function(e){
    e.preventDefault();
    var formSubmiter = new FormSubmiter(checkIdNumberUrl);
    $("#divBtnCheckId").hide(200, function(){
        $("#divBtnCheckIdWaiting").show(200);
    });
    formSubmiter.execute($(this), function (response){
        $("#successBoxMessage").html(response.message);
        $("#successBox").dialog(dialogBoxSettings);
    }, function(){
        $("#divBtnCheckIdWaiting").hide(200, function(){
            $("#divBtnCheckId").show(200);
        });
    });
});