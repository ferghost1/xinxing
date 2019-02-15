function close_box(id)
{
    $("#"+id).css("display","none");
}

function search_acc(id)
{
    var value=$("#"+id).val();
    window.location=$(location).attr('href')+"&s="+value;
}

function up_page()
{
    $('body,html').animate({
        scrollTop: 0
    })
}

function editnumber(id)
{
    var obj=$("#"+id);
    var string=obj.val();
    var string=cupnumber(string);
    var new_string='';
    var j=0;
    for(var i=string.length-1;i>=0;i--)
    {
        if(j%3==0&&j!=0) new_string+=' ,';
        new_string+=string[i];
        j++;
    }
    new_string=reverse(new_string);

    obj.val(new_string);
}

function eidtnumbers(number)
{
    var number=cupnumber(number);
    var new_string='';
    var j=0;
    for(var i=number.length-1;i>=0;i--)
    {
        if(j%3==0&&j!=0) new_string+=' ,';
        new_string+=number[i];
        j++;
    }
    new_string=reverse(new_string);
    return new_string;
}

function editnumberpercent(e,id)
{
    var key = e.which;
    var obj=$("#"+id);
    var string=obj.val();

    if (key == 8) {
        string=string.substr(0,string.length-2);
    }

    var string=cupnumberpercent(string);
    obj.val(string+" %");
}

function cupnumberpercent(string)
{
    string=string.toString();
    var new_string='';
    var f=false;
    if(string.length>0&string[0]==".") string="0.";
    for(var i=0;i<string.length;i++)
    {
        if(checknumberpercent(string[i]))
        {
            if(string[i]=="."&f)continue;
            new_string+=string[i];
        }
        if(string[i]==".") f=true;
    }
        
    return new_string;
}

function cupnumber(string)
{
    string=string.toString();
    var new_string='';
    for(var i=0;i<string.length;i++)
        if(checknumber(string[i])) new_string+=string[i];
    return new_string;
}

function reverse (s) {
    var o = '';
    for (var i = s.length - 1; i >= 0; i--)
        o += s[i];
    return o;
}

function checknumber(number)
{
    switch(number)
    {
        case '0':
            return true;
            break;
        case '1':
            return true;
            break;
        case '2':
            return true;
            break;
        case '3':
            return true;
            break;
        case '4':
            return true;
            break;
        case '5':
            return true;
            break;
        case '6':
            return true;
            break;
        case '7':
            return true;
            break;
        case '8':
            return true;
            break;
        case '9':
            return true;
            break;
        default:
            return false;
            break;
    }
    return false;
}

function checknumberpercent(number)
{
    switch(number)
    {
        case '0':
            return true;
            break;
        case '1':
            return true;
            break;
        case '2':
            return true;
            break;
        case '3':
            return true;
            break;
        case '4':
            return true;
            break;
        case '5':
            return true;
            break;
        case '6':
            return true;
            break;
        case '7':
            return true;
            break;
        case '8':
            return true;
            break;
        case '9':
            return true;
            break;
        case '.':
            return true;
            break;
        default:
            return false;
            break;
    }
    return false;
}

function get_date_now()
{
    var d = new Date();
    time = d.getFullYear() + "-" + check_month(parseInt(d.getMonth())+parseInt(1));
    return time;
}

function get_time_now()
{
    var d = new Date();
    time = d.getFullYear() + "-" + check_month(parseInt(d.getMonth())+parseInt(1))+"-"+check_month(parseInt(d.getDay()+parseInt(1)));
    return time;
}

function check_month(month)
{
    switch(month)
    {
        case 1:
            return '01';
            break;
        case 2:
            return '02';
            break;
        case 3:
            return '03';
            break;
        case 4:
            return '04';
            break;
        case 5:
            return '05';
            break;
        case 6:
            return '06';
            break;
        case 7:
            return '07';
            break;
        case 8:
            return '08';
            break;
        case 9:
            return '09';
            break;
        default:
            return month;
    }   
}