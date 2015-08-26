var locales = {
	'ar' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "\u0635",
			  "\u0645"
			],
			"DAY": [
			  "\u0627\u0644\u0623\u062d\u062f",
			  "\u0627\u0644\u0627\u062b\u0646\u064a\u0646",
			  "\u0627\u0644\u062b\u0644\u0627\u062b\u0627\u0621",
			  "\u0627\u0644\u0623\u0631\u0628\u0639\u0627\u0621",
			  "\u0627\u0644\u062e\u0645\u064a\u0633",
			  "\u0627\u0644\u062c\u0645\u0639\u0629",
			  "\u0627\u0644\u0633\u0628\u062a"
			],
			"MONTH": [
			  "\u064a\u0646\u0627\u064a\u0631",
			  "\u0641\u0628\u0631\u0627\u064a\u0631",
			  "\u0645\u0627\u0631\u0633",
			  "\u0623\u0628\u0631\u064a\u0644",
			  "\u0645\u0627\u064a\u0648",
			  "\u064a\u0648\u0646\u064a\u0648",
			  "\u064a\u0648\u0644\u064a\u0648",
			  "\u0623\u063a\u0633\u0637\u0633",
			  "\u0633\u0628\u062a\u0645\u0628\u0631",
			  "\u0623\u0643\u062a\u0648\u0628\u0631",
			  "\u0646\u0648\u0641\u0645\u0628\u0631",
			  "\u062f\u064a\u0633\u0645\u0628\u0631"
			],
			"SHORTDAY": [
			  "\u0627\u0644\u0623\u062d\u062f",
			  "\u0627\u0644\u0627\u062b\u0646\u064a\u0646",
			  "\u0627\u0644\u062b\u0644\u0627\u062b\u0627\u0621",
			  "\u0627\u0644\u0623\u0631\u0628\u0639\u0627\u0621",
			  "\u0627\u0644\u062e\u0645\u064a\u0633",
			  "\u0627\u0644\u062c\u0645\u0639\u0629",
			  "\u0627\u0644\u0633\u0628\u062a"
			],
			"SHORTMONTH": [
			  "\u064a\u0646\u0627\u064a\u0631",
			  "\u0641\u0628\u0631\u0627\u064a\u0631",
			  "\u0645\u0627\u0631\u0633",
			  "\u0623\u0628\u0631\u064a\u0644",
			  "\u0645\u0627\u064a\u0648",
			  "\u064a\u0648\u0646\u064a\u0648",
			  "\u064a\u0648\u0644\u064a\u0648",
			  "\u0623\u063a\u0633\u0637\u0633",
			  "\u0633\u0628\u062a\u0645\u0628\u0631",
			  "\u0623\u0643\u062a\u0648\u0628\u0631",
			  "\u0646\u0648\u0641\u0645\u0628\u0631",
			  "\u062f\u064a\u0633\u0645\u0628\u0631"
			],
			"fullDate": "EEEE\u060c d MMMM\u060c y",
			"longDate": "d MMMM\u060c y",
			"medium": "dd\u200f/MM\u200f/yyyy h:mm:ss a",
			"mediumDate": "dd\u200f/MM\u200f/yyyy",
			"mediumTime": "h:mm:ss a",
			"short": "d\u200f/M\u200f/yyyy h:mm a",
			"shortDate": "d\u200f/M\u200f/yyyy",
			"shortTime": "h:mm a"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "\u00a3",
			"DECIMAL_SEP": "\u066b",
			"GROUP_SEP": "\u066c",
			"PATTERNS": [
			  {
				"gSize": 0,
				"lgSize": 0,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "",
				"negSuf": "-",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 0,
				"lgSize": 0,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "\u00a4\u00a0",
				"negSuf": "-",
				"posPre": "\u00a4\u00a0",
				"posSuf": ""
			  }
			]
		},
		"id": "ar",
		"pluralCat": function (n) {  if (n == 0) {   return "zero";  }  if (n == 1) {   return "one";  }  if (n == 2) {   return "two";  }  if (n == (n | 0) && n % 100 >= 3 && n % 100 <= 10) {   return "few";  }  if (n == (n | 0) && n % 100 >= 11 && n % 100 <= 99) {   return "many";  }  return "other";}
	},
	'bg' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "\u043f\u0440. \u043e\u0431.",
			  "\u0441\u043b. \u043e\u0431."
			],
			"DAY": [
			  "\u043d\u0435\u0434\u0435\u043b\u044f",
			  "\u043f\u043e\u043d\u0435\u0434\u0435\u043b\u043d\u0438\u043a",
			  "\u0432\u0442\u043e\u0440\u043d\u0438\u043a",
			  "\u0441\u0440\u044f\u0434\u0430",
			  "\u0447\u0435\u0442\u0432\u044a\u0440\u0442\u044a\u043a",
			  "\u043f\u0435\u0442\u044a\u043a",
			  "\u0441\u044a\u0431\u043e\u0442\u0430"
			],
			"MONTH": [
			  "\u044f\u043d\u0443\u0430\u0440\u0438",
			  "\u0444\u0435\u0432\u0440\u0443\u0430\u0440\u0438",
			  "\u043c\u0430\u0440\u0442",
			  "\u0430\u043f\u0440\u0438\u043b",
			  "\u043c\u0430\u0439",
			  "\u044e\u043d\u0438",
			  "\u044e\u043b\u0438",
			  "\u0430\u0432\u0433\u0443\u0441\u0442",
			  "\u0441\u0435\u043f\u0442\u0435\u043c\u0432\u0440\u0438",
			  "\u043e\u043a\u0442\u043e\u043c\u0432\u0440\u0438",
			  "\u043d\u043e\u0435\u043c\u0432\u0440\u0438",
			  "\u0434\u0435\u043a\u0435\u043c\u0432\u0440\u0438"
			],
			"SHORTDAY": [
			  "\u043d\u0434",
			  "\u043f\u043d",
			  "\u0432\u0442",
			  "\u0441\u0440",
			  "\u0447\u0442",
			  "\u043f\u0442",
			  "\u0441\u0431"
			],
			"SHORTMONTH": [
			  "\u044f\u043d.",
			  "\u0444\u0435\u0432\u0440.",
			  "\u043c\u0430\u0440\u0442",
			  "\u0430\u043f\u0440.",
			  "\u043c\u0430\u0439",
			  "\u044e\u043d\u0438",
			  "\u044e\u043b\u0438",
			  "\u0430\u0432\u0433.",
			  "\u0441\u0435\u043f\u0442.",
			  "\u043e\u043a\u0442.",
			  "\u043d\u043e\u0435\u043c.",
			  "\u0434\u0435\u043a."
			],
			"fullDate": "dd MMMM y, EEEE",
			"longDate": "dd MMMM y",
			"medium": "dd.MM.yyyy HH:mm:ss",
			"mediumDate": "dd.MM.yyyy",
			"mediumTime": "HH:mm:ss",
			"short": "dd.MM.yy HH:mm",
			"shortDate": "dd.MM.yy",
			"shortTime": "HH:mm"
		  },
		  "NUMBER_FORMATS": {
			"CURRENCY_SYM": "lev",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": "\u00a0",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "\u00a0\u00a4",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "bg",
		"pluralCat": function (n) {  if (n == 1) {   return "one";  }  return "other";}
	},
	'de' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "vorm.",
			  "nachm."
			],
			"DAY": [
			  "Sonntag",
			  "Montag",
			  "Dienstag",
			  "Mittwoch",
			  "Donnerstag",
			  "Freitag",
			  "Samstag"
			],
			"MONTH": [
			  "Januar",
			  "Februar",
			  "M\u00e4rz",
			  "April",
			  "Mai",
			  "Juni",
			  "Juli",
			  "August",
			  "September",
			  "Oktober",
			  "November",
			  "Dezember"
			],
			"SHORTDAY": [
			  "So.",
			  "Mo.",
			  "Di.",
			  "Mi.",
			  "Do.",
			  "Fr.",
			  "Sa."
			],
			"SHORTMONTH": [
			  "Jan",
			  "Feb",
			  "M\u00e4r",
			  "Apr",
			  "Mai",
			  "Jun",
			  "Jul",
			  "Aug",
			  "Sep",
			  "Okt",
			  "Nov",
			  "Dez"
			],
			"fullDate": "EEEE, d. MMMM y",
			"longDate": "d. MMMM y",
			"medium": "dd.MM.yyyy HH:mm:ss",
			"mediumDate": "dd.MM.yyyy",
			"mediumTime": "HH:mm:ss",
			"short": "dd.MM.yy HH:mm",
			"shortDate": "dd.MM.yy",
			"shortTime": "HH:mm"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "\u20ac",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": ".",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "\u00a0\u00a4",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "de-de",
		"pluralCat": function (n) {  if (n == 1) {   return "one";  }  return "other";}
	},
	'en' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "AM",
			  "PM"
			],
			"DAY": [
			  "Sunday",
			  "Monday",
			  "Tuesday",
			  "Wednesday",
			  "Thursday",
			  "Friday",
			  "Saturday"
			],
			"MONTH": [
			  "January",
			  "February",
			  "March",
			  "April",
			  "May",
			  "June",
			  "July",
			  "August",
			  "September",
			  "October",
			  "November",
			  "December"
			],
			"SHORTDAY": [
			  "Sun",
			  "Mon",
			  "Tue",
			  "Wed",
			  "Thu",
			  "Fri",
			  "Sat"
			],
			"SHORTMONTH": [
			  "Jan",
			  "Feb",
			  "Mar",
			  "Apr",
			  "May",
			  "Jun",
			  "Jul",
			  "Aug",
			  "Sep",
			  "Oct",
			  "Nov",
			  "Dec"
			],
			"fullDate": "EEEE, MMMM d, y",
			"longDate": "MMMM d, y",
			"medium": "MMM d, y h:mm:ss a",
			"mediumDate": "MMM d, y",
			"mediumTime": "h:mm:ss a",
			"short": "M/d/yy h:mm a",
			"shortDate": "M/d/yy",
			"shortTime": "h:mm a"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "$",
			"DECIMAL_SEP": ".",
			"GROUP_SEP": ",",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "(\u00a4",
				"negSuf": ")",
				"posPre": "\u00a4",
				"posSuf": ""
			  }
			]
		},
		"id": "en-us",
		"pluralCat": function (n) {  if (n == 1) {   return "one";  }  return "other";}
	},
	'es' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "a.m.",
			  "p.m."
			],
			"DAY": [
			  "domingo",
			  "lunes",
			  "martes",
			  "mi\u00e9rcoles",
			  "jueves",
			  "viernes",
			  "s\u00e1bado"
			],
			"MONTH": [
			  "enero",
			  "febrero",
			  "marzo",
			  "abril",
			  "mayo",
			  "junio",
			  "julio",
			  "agosto",
			  "septiembre",
			  "octubre",
			  "noviembre",
			  "diciembre"
			],
			"SHORTDAY": [
			  "dom",
			  "lun",
			  "mar",
			  "mi\u00e9",
			  "jue",
			  "vie",
			  "s\u00e1b"
			],
			"SHORTMONTH": [
			  "ene",
			  "feb",
			  "mar",
			  "abr",
			  "may",
			  "jun",
			  "jul",
			  "ago",
			  "sep",
			  "oct",
			  "nov",
			  "dic"
			],
			"fullDate": "EEEE, d 'de' MMMM 'de' y",
			"longDate": "d 'de' MMMM 'de' y",
			"medium": "dd/MM/yyyy HH:mm:ss",
			"mediumDate": "dd/MM/yyyy",
			"mediumTime": "HH:mm:ss",
			"short": "dd/MM/yy HH:mm",
			"shortDate": "dd/MM/yy",
			"shortTime": "HH:mm"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "\u20ac",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": ".",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "\u00a0\u00a4",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "es",
		"pluralCat": function (n) {  if (n == 1) {   return "one";  }  return "other";}
	},
	'fr' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "AM",
			  "PM"
			],
			"DAY": [
			  "dimanche",
			  "lundi",
			  "mardi",
			  "mercredi",
			  "jeudi",
			  "vendredi",
			  "samedi"
			],
			"MONTH": [
			  "janvier",
			  "f\u00e9vrier",
			  "mars",
			  "avril",
			  "mai",
			  "juin",
			  "juillet",
			  "ao\u00fbt",
			  "septembre",
			  "octobre",
			  "novembre",
			  "d\u00e9cembre"
			],
			"SHORTDAY": [
			  "dim.",
			  "lun.",
			  "mar.",
			  "mer.",
			  "jeu.",
			  "ven.",
			  "sam."
			],
			"SHORTMONTH": [
			  "janv.",
			  "f\u00e9vr.",
			  "mars",
			  "avr.",
			  "mai",
			  "juin",
			  "juil.",
			  "ao\u00fbt",
			  "sept.",
			  "oct.",
			  "nov.",
			  "d\u00e9c."
			],
			"fullDate": "EEEE d MMMM y",
			"longDate": "d MMMM y",
			"medium": "d MMM y HH:mm:ss",
			"mediumDate": "d MMM y",
			"mediumTime": "HH:mm:ss",
			"short": "dd/MM/yy HH:mm",
			"shortDate": "dd/MM/yy",
			"shortTime": "HH:mm"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "\u20ac",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": "\u00a0",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "(",
				"negSuf": "\u00a0\u00a4)",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "fr",
		"pluralCat": function (n) {  if (n >= 0 && n <= 2 && n != 2) {   return "one";  }  return "other";}
	},
	'ru' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "\u0434\u043e \u043f\u043e\u043b\u0443\u0434\u043d\u044f",
			  "\u043f\u043e\u0441\u043b\u0435 \u043f\u043e\u043b\u0443\u0434\u043d\u044f"
			],
			"DAY": [
			  "\u0432\u043e\u0441\u043a\u0440\u0435\u0441\u0435\u043d\u044c\u0435",
			  "\u043f\u043e\u043d\u0435\u0434\u0435\u043b\u044c\u043d\u0438\u043a",
			  "\u0432\u0442\u043e\u0440\u043d\u0438\u043a",
			  "\u0441\u0440\u0435\u0434\u0430",
			  "\u0447\u0435\u0442\u0432\u0435\u0440\u0433",
			  "\u043f\u044f\u0442\u043d\u0438\u0446\u0430",
			  "\u0441\u0443\u0431\u0431\u043e\u0442\u0430"
			],
			"MONTH": [
			  "\u044f\u043d\u0432\u0430\u0440\u044f",
			  "\u0444\u0435\u0432\u0440\u0430\u043b\u044f",
			  "\u043c\u0430\u0440\u0442\u0430",
			  "\u0430\u043f\u0440\u0435\u043b\u044f",
			  "\u043c\u0430\u044f",
			  "\u0438\u044e\u043d\u044f",
			  "\u0438\u044e\u043b\u044f",
			  "\u0430\u0432\u0433\u0443\u0441\u0442\u0430",
			  "\u0441\u0435\u043d\u0442\u044f\u0431\u0440\u044f",
			  "\u043e\u043a\u0442\u044f\u0431\u0440\u044f",
			  "\u043d\u043e\u044f\u0431\u0440\u044f",
			  "\u0434\u0435\u043a\u0430\u0431\u0440\u044f"
			],
			"SHORTDAY": [
			  "\u0432\u0441",
			  "\u043f\u043d",
			  "\u0432\u0442",
			  "\u0441\u0440",
			  "\u0447\u0442",
			  "\u043f\u0442",
			  "\u0441\u0431"
			],
			"SHORTMONTH": [
			  "\u044f\u043d\u0432.",
			  "\u0444\u0435\u0432\u0440.",
			  "\u043c\u0430\u0440\u0442\u0430",
			  "\u0430\u043f\u0440.",
			  "\u043c\u0430\u044f",
			  "\u0438\u044e\u043d\u044f",
			  "\u0438\u044e\u043b\u044f",
			  "\u0430\u0432\u0433.",
			  "\u0441\u0435\u043d\u0442.",
			  "\u043e\u043a\u0442.",
			  "\u043d\u043e\u044f\u0431.",
			  "\u0434\u0435\u043a."
			],
			"fullDate": "EEEE, d MMMM y\u00a0'\u0433'.",
			"longDate": "d MMMM y\u00a0'\u0433'.",
			"medium": "dd.MM.yyyy H:mm:ss",
			"mediumDate": "dd.MM.yyyy",
			"mediumTime": "H:mm:ss",
			"short": "dd.MM.yy H:mm",
			"shortDate": "dd.MM.yy",
			"shortTime": "H:mm"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "\u0440\u0443\u0431.",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": "\u00a0",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "\u00a0\u00a4",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "ru-ru",
		"pluralCat": function (n) {  if (n % 10 == 1 && n % 100 != 11) {   return "one";  }  if (n == (n | 0) && n % 10 >= 2 && n % 10 <= 4 && (n % 100 < 12 || n % 100 > 14)) {   return "few";  }  if (n % 10 == 0 || n == (n | 0) && n % 10 >= 5 && n % 10 <= 9 || n == (n | 0) && n % 100 >= 11 && n % 100 <= 14) {   return "many";  }  return "other";}
	},
	'tr' : {
		"DATETIME_FORMATS": {
			"AMPMS": [
			  "AM",
			  "PM"
			],
			"DAY": [
			  "Pazar",
			  "Pazartesi",
			  "Sal\u0131",
			  "\u00c7ar\u015famba",
			  "Per\u015fembe",
			  "Cuma",
			  "Cumartesi"
			],
			"MONTH": [
			  "Ocak",
			  "\u015eubat",
			  "Mart",
			  "Nisan",
			  "May\u0131s",
			  "Haziran",
			  "Temmuz",
			  "A\u011fustos",
			  "Eyl\u00fcl",
			  "Ekim",
			  "Kas\u0131m",
			  "Aral\u0131k"
			],
			"SHORTDAY": [
			  "Paz",
			  "Pzt",
			  "Sal",
			  "\u00c7ar",
			  "Per",
			  "Cum",
			  "Cmt"
			],
			"SHORTMONTH": [
			  "Oca",
			  "\u015eub",
			  "Mar",
			  "Nis",
			  "May",
			  "Haz",
			  "Tem",
			  "A\u011fu",
			  "Eyl",
			  "Eki",
			  "Kas",
			  "Ara"
			],
			"fullDate": "d MMMM y EEEE",
			"longDate": "d MMMM y",
			"medium": "d MMM y HH:mm:ss",
			"mediumDate": "d MMM y",
			"mediumTime": "HH:mm:ss",
			"short": "dd MM yyyy HH:mm",
			"shortDate": "dd MM yyyy",
			"shortTime": "HH:mm"
		},
		"NUMBER_FORMATS": {
			"CURRENCY_SYM": "TL",
			"DECIMAL_SEP": ",",
			"GROUP_SEP": ".",
			"PATTERNS": [
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 3,
				"minFrac": 0,
				"minInt": 1,
				"negPre": "-",
				"negSuf": "",
				"posPre": "",
				"posSuf": ""
			  },
			  {
				"gSize": 3,
				"lgSize": 3,
				"macFrac": 0,
				"maxFrac": 2,
				"minFrac": 2,
				"minInt": 1,
				"negPre": "(",
				"negSuf": "\u00a0\u00a4)",
				"posPre": "",
				"posSuf": "\u00a0\u00a4"
			  }
			]
		},
		"id": "tr",
		"pluralCat": function (n) {  return "other";}
	},
	
}

function getLocales(){
	return locales;
}
