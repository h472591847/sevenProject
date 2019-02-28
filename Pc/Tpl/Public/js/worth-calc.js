(function(){
	var wt = $('#worthTopics');
	var wd = $('#worthData');
	var wr = $('#worthRecord');
	var ws = $('#worthSubmint');
	var array = [
		["1","您当前的存款数额"],
		["2","您当前的月工资"],
		["3","您每月工资的存款比例"],
		["4","您希望进行身价预估的时间"]
	];
	function createSubject(count){
		var html = '';
		var perCount = 0;
		var len = array.length;
		if(count < len){
			html += '<h2>'+array[count][0]+'.'+array[count][1]+'：</h2>';
			html += '<div class="worth-input">';
			if(count == 0){
				html += '<input type="text" id="curValue_'+count+'" class="input earn_txt">';//请输入大于0的任意数值
				html += ' <label>元</label>';
				html += '<div class="tip error" id="errorTip_'+count+'"></div>';
			} else if(count == 1){
				html += '<input type="text" id="curValue_'+count+'" class="input earn_txt">';
				html += ' <label>元</label>';
				html += '<div class="tip error" id="errorTip_'+count+'"></div>';
			} else if(count == 2){
				html += '<select id="curValue_'+count+'" class="earn_txt1">';
				for(var i=0; i<=20; i++){
					html += '<option>'+perCount+'</option>';
					perCount = perCount + 5;
				};
				html += '</select>';
				html += ' <label>%</label>';
				html += '<div class="tip error" id="errorTip_'+count+'"></div>';
			} else if(count == 3){
				html += '<select id="curValue_'+count+'" class="earn_txt1">';
				html += '<option value="1">1</option>';
				html += '<option value="2">2</option>';
				html += '<option value="5">5</option>';
				html += '<option value="10">10</option>';
				html += '<option value="20">20</option>';
				html += '<option value="30">30</option>';
				html += '</select>';
				html += ' <label>年后</label>';
				html += '<div class="tip error" id="errorTip_'+count+'"></div>';
			};
			html += '</div>';
			html += '<div class="worth-submint">';
			html += '<a href="javascript:;" id="NextTopic" data-rel="'+count+'" class="gbtn">'+(count == array.length - 1 ? '完成':'下一题')+'</a>';
			//html += '<a href="javascript:;" class="gbtn gbtn-gray">重新计算</a>';
			html += '</div>';
			wd.hide();
			wt.html(html).fadeIn();
		} else {
			wt.hide();
			var ret = wd.find('ul');
			html += '<li style="font-size:13px;padding:20px 0 10px 0">正在预估您的未来身价，请稍候...</li>';
			//html += '<li><div id="loadingBar" class="load-bar"><div></div></div></li>';
			ret.html(html);
			loadingBar(ret, len);
			worthCharting('计算中', len);
			wd.show();
		};
		docEventTodo(len);
	};
	function docEventTodo(len){
		wt.find('#NextTopic').click(function(){
			var par = parseInt($(this).attr('data-rel'));
			var tar = $('#curValue_'+par);
			var tip = $('#errorTip_'+par);
			if(!(tar.val() == '' || tar.val() == null)){
				if(par == 0 || par == 1){
					checking(tar, par, len, tip)
				} else {
					getNext(tar, par, len)
				};
			} else {
				tar.addClass('inputErr');
				tip.html('<i class="icons reg-error"></i>请输入大于0的任意数值！').show();
				tar.focus();
			}
		});
		wd.find('#resetCount').click(function(){
			worthCharting(0, 0);
			createSubject(0);
			wr.html('');
			ws.hide();
		});
	};
	//检查输入值合法性
	function checking(tar, par, len, tip){
		if(isNaN(tar.val()*1) || tar.val()*1 <= 0) {
			tip.html('<i class="icons reg-error"></i>输入有误，请输入大于0的任意数值！').show();
			tar.addClass('inputErr');
			tar.select();
			tar.focus();
			return false;
		} else {
			getNext(tar, par, len)
		}
	};
	//下一题
	function getNext(tar, par, len){
		var val = tar.val() + (par == len - 1 ? '' : ',');
		wr.append(val);
		worthCharting(0, par+1);
		createSubject(par+1);
	};
	//计算综合结果
	function worthResult(ret,len){
		var val = wr.html().split(',');
		var curStore = parseInt(val[0]);//您当前的存款数额
		var curWage = parseInt(val[1]);//您当前的月工资
		var storePer = parseInt(val[2]);//您每月工资的存款比例
		var estTime = parseInt(val[3]);//您希望进行身价预估的时间
		var r = 1/100;
		var d = Math.pow(1 + r, estTime * 12);
		var P = curStore * d; //当前身价到期后的价值
		var P2 = (curWage * (storePer/100)) * ((d - 1) / r); //计算每个月的存款及到期后的价值
		var pSum = P + P2;
		var sum = Util.numFormat(pSum, -1); //价值		
		var grow = Util.numFormat(((pSum - curStore) / curStore) * 100, -1); //增长
		var rank;
		if(pSum < 28000){
			rank = Util.numFormat((pSum / 560) + 1, -1);
		} else if(pSum > 28000 && pSum < 200000){
			rank = Util.numFormat((pSum - 28000) / 3822 + 50, -1);
		} else if(pSum > 200000 && pSum < 1000000){
			rank = Util.numFormat((pSum - 200000) / 200000 + 95, -1);
		} else if(pSum > 1000000){
			rank = 99
		};
		worthCharting(sum, len);
		var html = '<li>坚持在【金投资】投资，'+estTime+'年后您的身价将为<span class="light-org">'+sum+'</span>元，增长<span class="light-org">'+grow+'%</span></li>';
		html += '<li>财富排名超过<span class="light-org">'+rank+'%</span>中国人</li>';
		ret.html(html);
	};
	function loadingBar(ret, len){
		var bar = 'loadingBar';
		var setBar = function (count) {
			if (count) {
				$('#'+bar+'>div').css('width',String(count)+'%');
				$('#'+bar+'>div').html(String(count)+'%')
			}
		};
		var index = 0;
		var doBar = function () {
			if (index > 100) {
				worthResult(ret, len);
				ws.fadeIn();
				return
			};
			if (index <= 100) {
				setTimeout(doBar, 40);
				setBar(index);
				index++
			};
		};
		doBar();
	};
	function worthCharting(worth, step){
		$('.worth-chart').html('<div class="worth-div worth-'+step+'"><h2>当前身价(元)</h2><strong>'+worth+'</strong></div>');
	};
	worthCharting(0, 0);
	createSubject(0);
})();