String.prototype.replaceAll = function(oldValue, newValue) {
    var re = (typeof oldValue === "string") && (new RegExp(oldValue, "g")) || oldValue;
    return this.replace(re, newValue);
};

String.prototype.string = function (len) {
    var s = '', i = 0;
    while (i++ < len) {
        s += this;
    }
    return s;
};

String.prototype.zf = function (len) {
    return '0'.string(len - this.length) + this;
};

Number.prototype.zf = function (len) {
    return this.toString().zf(len);
};

Date.prototype.convertDBDateToViewDate = function (strDBDate, format) {
    let tempDate;

    if(format) {
        tempDate = new Date(strDBDate).format(format);
    }
    else {
        tempDate = new Date(strDBDate);
    }

    return tempDate.toString();    
}

Date.prototype.format = function (f) {
    if (!this.valueOf()) return '';

    var weekName = ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'];
    var d = this;

    return f.replace(/(yyyy|yy|MM|dd|E|hh|mm|ss|a\/p)/gi, function ($1) {
        switch ($1) {
            case "yyyy":
                return d.getFullYear();
            case "yy":
                return (d.getFullYear() % 1000).zf(2);
            case "MM":
                return (d.getMonth() + 1).zf(2);
            case "dd":
                return d.getDate().zf(2);
            case "E":
                return weekName[d.getDay()];
            case "HH":
                return d.getHours().zf(2);
            case "hh":
                return ((h = d.getHours() % 12) ? h : 12).zf(2);
            case "mm":
                return d.getMinutes().zf(2);
            case "ss":
                return d.getSeconds().zf(2);
            case "a/p":
                return d.getHours() < 12 ? '오전' : '오후';
            default:
                return $1;
        }
    });
};

function isEmpty (param) {

    // 일반적인 비어있는지 검사
    if (!param || param == null || param == undefined || param == '' || param.length == 0) {
        return true;
    }
    // Object 인데 비어 있을 수 있으므로 ( param = {}; )
    else {
        // object 형 이라면
        if (String(typeof param).toLowerCase() === 'object') {
            // key 를 추출하여 key length 검사
            if (Object.keys(param).length === 0) {
                return true;
            } else {
                return false;
            }
        }
    }
};

function stoppingBubblingEvent(event) {
    // 현재 이벤트 기본 동작 중단
    event.preventDefault();

    // 현재 이벤트가 상위로 전파되지 않도록 중단
    event.stopPropagation();

    return false;
}

function getCookie(_name) {
	let cookie_name = _name + '=';
	
	let x = 0;

	while(x <= document.cookie.length) {
		let y = (x + cookie_name.length);

		if(document.cookie.substring(x, y) == cookie_name) {
			let endOfCookie = document.cookie.indexOf(';', y);
			if( endOfCookie == -1) {
				endOfCookie = document.cookie.length;
			}

			return unescape(document.cookie.substring(y, endOfCookie));
		}

		x = document.cookie.indexOf(' ', x) + 1;

		if(x == 0) {
			break;
		}
	}

	return '';
}


function setSpinner(isView /* 1/true: show / 0/false: hide */) {
	if(isView) { showSpinner(); }
	else { hideSpinner(); }
}

function showSpinner() {
	$('#spinner-bk').show();
	$('#spinner').spin('show');
	$('body').off('scroll touchmove mousewheel');
	// $('body').on('scroll touchmove mousewheel', function(event) {
	// 	event.preventDefault();
	// 	event.stopPropagation();
	// 	return false;
	// });
}

function hideSpinner() {
	$('#spinner-bk').hide();
	$('#spinner').spin('hide');
	$('body').on('scroll touchmove mousewheel');
	// $('body').on('scroll touchmove mousewheel', function(event) {		
	// 	return true;
	// });
}

document.addEventListener("DOMContentLoaded", function(){
	// Handler when the DOM is fully loaded
	setSpinner(0);
});


