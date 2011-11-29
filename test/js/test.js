test("uninitialized string locale", function() {
	equal("car", "car".locale());
});
test("uninitialized empty string locale", function() {
	equal("", "".locale());
});
test("uninitialized parametrized string locale", function() {
	equal("text 1", __("text %s", 1));
});
test("uninitialized empty array locale", function() {
	equal("", [].locale(1));
});
test("uninitialized array locale", function() {
	equal("text0", ["text0", "text1"].locale(1));
	equal("text1", ["text0", "text1"].locale(2));
});
test("uninitialized parametrized array locale", function() {
	equal("text0 1", __n("text0 %s", "text1 %s", 1));
	equal("text1 2", __n("text0 %s", "text1 %s", 2));
});
test("initialized string locale", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {
		'car' : 'auto'
	});
	equal("auto", "car".locale());
	equal("plane", "plane".locale());
});
test("empty uninitialized array locale", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {});
	equal("text0", ["text0", "text1"].locale(1));
	equal("text1", ["text0", "text1"].locale(2));
});
test("empty initialized string locale", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {});
	equal("plane", "plane".locale());
});
test("initialized parametrized string locale", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {
		'%s car' : '%s auto'
	});
	equal("ford auto", __('%s car', 'ford'));
	equal("fast plane", __('%s plane', 'fast'));
});
test("initialized parametrized array locale", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {
		'%d car' : ['%d auto', '%d auta', '%d aut']
	});
	equal("1 auto", __n('%d car', '%d cars', 1));
	equal("2 auta", __n('%d car', '%d cars', 2));
	equal("5 aut", __n('%d car', '%d cars', 5));
	equal("1 plane", __n('%d plane', '%d planes', 1));
	equal("2 planes", __n('%d plane', '%d planes', 2));
	equal("5 planes", __n('%d plane', '%d planes', 5));
});
test("initialized parametrized array locale, missing plural parameter", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {
		'%d car' : ['%d auto', '%d auta', '%d aut']
	});
	equal("__n(!!!missing plural argument!!!,%d car,1)", __n('%d car', 1));
	equal("__n(!!!missing plural argument!!!,%d car,2)", __n('%d car', 2));
	equal("__n(!!!missing plural argument!!!,%d car,5)", __n('%d car', 5));
	equal("__n(!!!missing plural argument!!!,%d plane,1)", __n('%d plane', 1));
	equal("__n(!!!missing plural argument!!!,%d plane,2)", __n('%d plane', 2));
	equal("__n(!!!missing plural argument!!!,%d plane,5)", __n('%d plane', 5));
});
test("initialized parametrized array locale, extra plural parameter", function() {
	String.locale(2, function(n) {
		return n < 2 ? 0 : 1;
	}, 3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, {
		'%d car' : ['%d auto', '%d auta', '%d aut']
	});
	equal("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,1)", __n('%d car', '%d cars', '%d cars', 1));
	equal("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,2)", __n('%d car', '%d cars', '%d cars', 2));
	equal("__n(!!!too many plurals!!!,%d car,%d cars,%d cars,5)", __n('%d car', '%d cars', '%d cars', 5));
	equal("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,1)", __n('%d plane', '%d planes', '%d planes', 1));
	equal("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,2)", __n('%d plane', '%d planes', '%d planes', 2));
	equal("__n(!!!too many plurals!!!,%d plane,%d planes,%d planes,5)", __n('%d plane', '%d planes', '%d planes', 5));
});
test("initialized parametrized array locale, default more than 2 plurals", function() {
	String.locale(3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, 2, function(n) {
		return n < 2 ? 0 : 1;
	}, {
		'%d auto' : ['%d car', '%d cars']
	});
	equal("1 car", __n('%d auto', '%d auta', '%d aut', 1));
	equal("2 cars", __n('%d auto', '%d auta', '%d aut', 2));
	equal("5 cars", __n('%d auto', '%d auta', '%d aut', 5));
	equal("1 letadlo", __n('%d letadlo', '%d letadla', '%d letadel', 1));
	equal("2 letadla", __n('%d letadlo', '%d letadla', '%d letadel', 2));
	equal("5 letadel", __n('%d letadlo', '%d letadla', '%d letadel', 5));
});
test("initialized parametrized array locale, missing plural parameter, default more than 2 plurals", function() {
	String.locale(3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, 2, function(n) {
		return n < 2 ? 0 : 1;
	}, {
		'%d auto' : ['%d car', '%d cars']
	});
	equal("__n(!!!missing plural argument!!!,%d auto,%d auta,1)", __n('%d auto', '%d auta', 1));
	equal("__n(!!!missing plural argument!!!,%d auto,%d auta,2)", __n('%d auto', '%d auta', 2));
	equal("__n(!!!missing plural argument!!!,%d auto,%d auta,5)", __n('%d auto', '%d auta', 5));
	equal("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,1)", __n('%d letadlo', '%d letadla', 1));
	equal("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,2)", __n('%d letadlo', '%d letadla', 2));
	equal("__n(!!!missing plural argument!!!,%d letadlo,%d letadla,5)", __n('%d letadlo', '%d letadla', 5));
});
test("initialized parametrized array locale, extra plural parameter, default more than 2 plurals", function() {
	String.locale(3, function(n) {
		return (n == 1) ? 0 : ((n >= 2 && n <= 4) ? 1 : 2)
	}, 2, function(n) {
		return n < 2 ? 0 : 1;
	}, {
		'%d auto' : ['%d car', '%d cars']
	});
	equal("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,1)", __n('%d auto', '%d auta', '%d aut', '%d aut', 1));
	equal("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,2)", __n('%d auto', '%d auta', '%d aut', '%d aut', 2));
	equal("__n(!!!too many plurals!!!,%d auto,%d auta,%d aut,%d aut,5)", __n('%d auto', '%d auta', '%d aut', '%d aut', 5));
	equal("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,1)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 1));
	equal("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,2)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 2));
	equal("__n(!!!too many plurals!!!,%d letadlo,%d letadla,%d letadel,%d letadel,5)", __n('%d letadlo', '%d letadla', '%d letadel', '%d letadel', 5));
});
