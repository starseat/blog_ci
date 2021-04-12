/**
 * version 0.1.1
 * (c) 2017 Lee JongWoo's Javascript Library
 */

/**
 * [jw description]
 * javascript 개발시 필요하거나 자주 쓰는 함수 및 기능 정리
 * 알기 쉬운 변수, 함수의 경우는 주석(설명) 하지 않음.
 */

var jw = jw || (function () {

    'use strict'; // es5

    var module = {};

    module.version = {
        full: '0.1.1',
        major: 0,
        minor: 1,
        dot: 1
    };


    /* ****************************************************************************************************
     * ****************************************    UTIL     ***********************************************
     * ***************************************************************************************************/

    /**
     * 검사 하는 함수들
     * @param value : 검사 대상 변수
     */
    module.isUndefined = function (value) {
        return typeof value === 'undefined';
    };
    module.isDefined = function (value) {
        return typeof value !== 'undefined';
    };
    module.isObject = function (value) {
        return value !== null && typeof value === 'object';
    };
    module.isBlankObject = function (value) {
        return value !== null && typeof value === 'object' && !getPrototypeOf(value);
    };
    module.isString = function (value) {
        return typeof value === 'string';
    };
    module.isNumber = function (value) {
        return typeof value === 'number';
    };
    module.isDate = function (value) {
        return toString.call(value) === '[object Date]';
    };
    module.isArray = Array.isArray;
    module.isFunction = function (value) {
        return typeof value === 'function';
    };
    module.isFile = function (obj) {
        return toString.call(obj) === '[object File]';
    };
    module.isBoolean = function (value) {
        return typeof value === 'boolean';
    };

    /**
     * 숫자 검사 다른버전
     * @param s : 숫자인지 검사할 문자열
     */
    module.isNumberEx = function (s) {
        s += ''; // 문자열로 변환
        s = s.replace(/^\s*|\s*$/g, ''); // 좌우 공백 제거
        if (s == '' || isNaN(s)) return false;
        return true;
    };

    /**
     * 비어있는 값인지 검사
     * @param param : 비어있는 값인지 검사할 파라미터
     */
    module.isEmpty = function (param) {

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

    /**
     * Object 로 되어 있는 것을 QueryString 로 변환
     * @param obj : 변환할 Object
     */
    module.getUriQueryString = function (obj) {
        var str = '';
        for (var key in obj) {
            if (str !== '') {
                str += '&';
            }
            str += key + '=' + obj[key];
        }
        return str;
    };

    /**
     * 10보다 작은 날은 앞에 0 붙여주기
     * @param number : 숫자값
     */
    module.addZeroDate = function (number) {
        if (parseInt(number, 10) < 10) {
            number = '0' + number;
        }
        return number;
    };

    /**
     * 숫자 앞에 0 붙여주기
     * @param number : 숫자값
     * @param digits : 0붙일 범위
     */
    module.addZeros = function (number, digits) {
        var zero = '';
        number = number.toString();

        if (number.length < digits) {
            var length = digits - number.length;
            for (var i = 0; i < length; i++) {
                zero += '0';
            }
        }

        return zero + number;
    };


    /**
     * 현재 날짜 구하기
     * @param type : 날짜 타입 (day(년월일)/date(년월일시분초)/ms(년월일시분초.ms), default:ms)
     * @param isSeparator : 구분자 (true/false)
     */
    module.getCurrentDate = function (_type, _isSeparator) {
        var type = _type || 'ms';
        var isSeparator = _isSeparator || true;

        var date = new Date();
        var strRet = '';
        var strTemp = '';
        var strDay = '';

        if (isSeparator) {
            strDay = date.getFullYear() + '-' +
                module.addZeroDate(parseInt(date.getMonth() + 1)) + '-' +
                module.addZeroDate(date.getDate());

            if (type === 'day') {
                strTemp = strDay;
            } else if (type === 'date') {
                strTemp = strDay + ' ' +
                    module.addZeroDate(date.getHours()) + ':' +
                    module.addZeroDate(date.getMinutes()) + ':' +
                    module.addZeroDate(date.getSeconds());
            } else {
                strTemp = strDay + ' ' +
                    module.addZeroDate(date.getHours()) + ':' +
                    module.addZeroDate(date.getMinutes()) + ':' +
                    module.addZeroDate(date.getSeconds()) + '.' +
                    date.getMilliseconds();
            }
        } else {
            strDay = date.getFullYear() +
                module.addZeroDate(Number(date.getMonth() + 1)) +
                module.addZeroDate(date.getDate());

            if (type === 'day') {
                strTemp = strDay;
            } else if (type === 'date') {
                strTemp = strDay +
                    module.addZeroDate(date.getHours()) +
                    module.addZeroDate(date.getMinutes()) +
                    module.addZeroDate(date.getSeconds());
            } else {
                strTemp = strDay +
                    module.addZeroDate(date.getHours()) +
                    module.addZeroDate(date.getMinutes()) +
                    module.addZeroDate(date.getSeconds()) +
                    date.getMilliseconds();
            }
        }

        strRet = strTemp;
        return strRet;
    };


    /**
     * 문자열의 바이트 수 구하기
     * @param text : 문자열
     */
    module.getBytes = function (text) {
        if (text === undefined || text === null || text === '') {
            return 0;
        }
        var count = 0;
        var charSize = 2;
        switch (document.charset.toLowerCase()) {
            case 'utf-8':
                charSize = 3;
                break;
            case 'euc-kr':
                charSize = 2;
                break;
            case 'ks_c_5601-1987':
                charSize = 2;
                break;
            default:
                charSize = 2;
                break;
        }
        for (var i = 0; i < text.length; i++) {
            if (text.charCodeAt(i) < 256) {
                count++;
            } else {
                count = count + charSize;
            }
        }
        return count;
    }

    /* ****************************************************************************************************
     * ************************************    쿠키(Cookie)    *********************************************
     * ***************************************************************************************************/

    var _cookie = {};

    /**
     * 쿠키값 추출
     * @param cookieName : 쿠키명
     */
    _cookie.get = function (cookieName) {
        var search = cookieName + '=';
        var cookie = document.cookie;
        var startIndex = 0;
        var endIndex = 0;

        // 현재 쿠키가 존재할 경우
        if (cookie.length > 0) {
            // 해당 쿠키명이 존재하는지 검색한 후 존재하면 위치를 리턴.
            startIndex = cookie.indexOf(search);

            // 만약 존재한다면
            if (startIndex > -1) {
                // 값을 얻어내기 위해 시작 인덱스 조절
                startIndex += cookieName.length;

                // 값을 얻어내기 위해 종료 인덱스 추출
                endIndex = cookie.indexOf(';', startIndex);

                // 만약 종료 인덱스를 못찾게 되면 쿠키 전체길이로 설정
                if (endIndex == -1) endIndex = cookie.length;

                // 쿠키값을 추출하여 리턴
                // return unescape( cookie.substring( startIndex + 1, endIndex ) );
                var resultCookie = decodeURIComponent(cookie.substring(startIndex + 1, endIndex));

                // 쿠키가 object 가 아닌 단일 값일 경우, 앞 뒤에 ' 가 붙으므로 제거
                if (resultCookie.charAt(0) === '') {
                    resultCookie = resultCookie.substring(1, resultCookie.length);
                }
                if (resultCookie.charAt(resultCookie.length - 1) === '') {
                    resultCookie = resultCookie.substring(0, resultCookie.length - 1);
                }

                return resultCookie;
            }
            // 쿠키 내에 해당 쿠키가 존재하지 않을 경우
            else {
                return false;
            }
        }
        // 쿠키 자체가 없을 경우
        else {
            return false;
        }
    };

    /**
     * 쿠키 설정
     * @param cookieName 	: 쿠키명
     * @param cookieValue 	: 쿠키값
     * @param expireDay 	: 쿠키 유효날짜
     */
    _cookie.set = function (cookieName, cookieValue, expireDate) {
        var today = new Date();
        today.setDate(today.getDate() + parseInt(expireDate));
        document.cookie = cookieName + '=' + escape(cookieValue) + '; path=/; expires=' + today.toGMTString() + ';';
    };

    /**
     * 쿠키 삭제
     * @param cookieName 삭제할 쿠키명
     */
    _cookie.delete = function (cookieName) {
        var expireDate = new Date();

        //어제 날짜를 쿠키 소멸 날짜로 설정한다.
        expireDate.setDate(expireDate.getDate() - 1);
        document.cookie = cookieName + '= ' + '; expires=' + expireDate.toGMTString() + '; path=/';
    };

    module.cookie = _cookie;

    /* ****************************************************************************************************
     * *******************************************    로그    **********************************************
     * ***************************************************************************************************/

    var _log = {
        isView: true,
        isDebug: true,
        isDate: true,
        isUseTag: true
    };

    /**
     * 로그 사용유무 설정
     * @param flag : 로드 사용 설정 flag
     */
    _log.setLogView = function (flag) {
        if (module.isDefined(flag)) {
            _log.isView = flag;
        }
    }

    /**
     * 로그 사용유무 설정 2
     * @param flag : 로드 사용 설정 flag
     */
    _log.enable = function (flag) {
        _log.setLogView(flag);
    }

    /**
     * 로그 Debug 모드 설정
     * @param flag : debug 모드 설정 flag
     */
    _log.setDebugMode = function (flag) {
        if (module.isDefined(flag)) {
            _log.isDebug = flag;
        }
    }

    /**
     * 로그 출력시 시간 표시 설정
     * @param flag : 시간 표시 설정 flag
     */
    _log.setAddDate = function (flag) {
        if (module.isDefined(flag)) {
            _log.isDate = flag;
        }
    }

    /**
     * 로그 사용시 태그 사용 설정
     * @param flag : 시간 표시 설정 flag
     */
    _log.setUseTag = function (flag) {
        if (module.isDefined(flag)) {
            _log.isUseTag = flag;
        }
    }

    /**
     * 로그 메시지 만듬
     * @param tag : 로그 태그
     * @param value(s) : 로그로 나타낼 변수들
     */
    _log.__makeLog = function ( /*logtype, tag, value[, value,...] */ ) {
        var logtype = arguments[0][0];
        var args = arguments[0][1];
        var value;

        var logMsg = '';
        var logIndex = 0;

        if (this.isDate) {
            logMsg += '[' + module.getCurrentDate() + '] ';
        }

        if (this.isUseTag) {
            logIndex = 1;
            logMsg += '[' + args[0] + '] ';
        } else {
            logIndex = 0;
        }

        for (length = args.length; logIndex < length; logIndex++) {
            if (args[logIndex] && typeof args[logIndex] == 'object') value = JSON.stringify(args[logIndex]);
            else value = args[logIndex];

            logMsg += value;
            if (logIndex < length - 1) logMsg += ', ';
        }

        return logMsg;
    };

    /**
     * 로그 호출
     * @param type : debug, log, info, warn, error 의 로그 타입
     * @param tag : 로그 태그
     * @param value(s) : 로그로 나타낼 변수들
     */
    _log.__callLog = function (type, /*tag, value[, value,...] */ ) {
        if (this.isView) {
            var console = window.console || {},
                logFn = console[type] || console.log;

            var logMsg = _log.__makeLog(arguments);
            logFn(logMsg);
        }
    };

    /**
     * 로그 레벨별 함수들
     * @param tag : 로그 태그
     * @param value(s) : 로그로 나타낼 변수들
     */
    _log.d = function ( /*tag, value[, value,...] */ ) {
        if (this.isDebug) {
            _log.__callLog('log', arguments);
        }
    };
    _log.l = function ( /*tag, value[, value,...] */ ) {
        if (this.isDebug) {
            _log.__callLog('log', arguments);
        }
    };
    _log.i = function ( /*tag, value[, value,...] */ ) {
        _log.__callLog('info', arguments);
    };
    _log.w = function ( /*tag, value[, value,...] */ ) {
        _log.__callLog('warn', arguments);
    };
    _log.e = function ( /*tag, value[, value,...] */ ) {
        _log.__callLog('error', arguments);
    };
    _log.t = function ( /*tag, value[, value,...] */ ) {
        _log.__callLog('trace', arguments);
    };
    _log.trace = function ( /*tag, value[, value,...] */ ) {
        _log.__callLog('trace', arguments);
    };

    module.log = _log;



    return module;

})();




/* ****************************************************************************************************
 * ************************************    Window UTIL     ********************************************
 * ***************************************************************************************************/


/**
 * [Window UTIL description]
 * 없을 수 있는 기능들 추가
 */
if (!('indexOf' in Array.prototype)) {
    Array.prototype.indexOf = function (find, i /* opt */ ) {
        'use strict';
        if (i === undefined) {
            i = 0;
        }
        if (i < 0) {
            i += this.length;
        }
        if (i < 0) {
            i = 0;
        }
        for (var n = this.length; i < n; i++) {
            if (i in this && this[i] === find) {
                return i;
            }
        }
        return -1;
    };
}

window['console'] = window['console'] || {};

JSON.stringify = JSON.stringify || function (obj) {
    var t = typeof (obj);
    if (t != 'object' || obj === null) {
        // simple data type
        if (t == 'string') {
            obj = '' + obj + '';
        }
        return String(obj);
    } else {
        // recurse array or object
        var n, v, json = [],
            arr = (obj && obj.constructor == Array);
        for (n in obj) {
            v = obj[n];
            t = typeof (v);
            if (t == 'string') {
                v = '' + v + '';
            } else if (t == 'object' && v !== null) {
                v = JSON.stringify(v);
            }
            json.push((arr ? '' : '' + n + ':') + String(v));
        }
        return (arr ? '[' : '{') + String(json) + (arr ? ']' : '}');
    }
};

String.prototype.replaceAll = function (oldValue, newValue) {
    var re = (typeof oldValue === 'string') && (new RegExp(oldValue, 'g')) || oldValue;
    return this.replace(re, newValue);
};

function trim(text) {
    if (text == null) {
        return '';
    }
    return text && text.toString().trim() || text;
};
String.prototype.trim = function () {
    return this.replace(/(^\s*)|(\s*$)/g, '');
};
String.prototype.trimLeft = function () {
    return this.replace(/^\s+/, '');
};
String.prototype.trimRight = function () {
    return this.replace(/\s+$/, '');
};