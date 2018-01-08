
			/*function add_zi1(){
				var tr1 = document.getElementById("tab1");
				var s1 = document.getElementsByName("zhicaidan1").length;
				if(s1 <= 4){
					$(tr1).after("<tr name='zhicaidan1'><td><input type='tel' value='11' /></td><td>——子：<input type='text'/></td><td><select onchange='getValue()' id='zhu1'><option value='0'>请选择</option><option value='1'>发送信息</option><option value='2'>跳转图文信息页</option><option value='3'>跳转链接</option></select></td><td><a href='html/wenben.html' id='zhu1_1' style='display: none;'><input type='text' style='border: none;' placeholder='点击编辑文本信息'/></a><a href='edit.html' id='zhu1_2' style='display: none;'><input value='点击编辑图文信息' type='button'/></a><input type='text' id='zhu1_3' style='display: none;' placeholder='请输入链接地址' /></td><td><input type='checkbox' /></td><td><a href='#'>删除</a></td></tr>");
				}else{
					return;
				}
			}
			function add_zi2(){
				var tr2 = document.getElementById("tab2");
				var s2 = document.getElementsByName("zhicaidan2").length;
				if(s2 <= 4){
					$(tr2).after("<tr name='zhicaidan2'><td><input type='tel' value='21' /></td><td>——子：<input type='text'/></td><td><select onchange='getValue1()' id='zhu1'><option value='0'>请选择</option><option value='1'>发送信息</option><option value='2'>跳转图文信息页</option><option value='3'>跳转链接</option></select></td><td><a href='html/wenben.html' id='zhu1_1' style='display: none;'><input type='text' style='border: none;' placeholder='点击编辑文本信息'/></a><a href='edit.html' id='zhu1_2' style='display: none;'><input value='点击编辑图文信息' type='button'/></a><input type='text' id='zhu1_3' style='display: none;' placeholder='请输入链接地址' /></td><td><input type='checkbox' /></td><td><a href='#'>删除</a></td></tr>");
				}
			}
			function add_zi3(){
				var tr3 = document.getElementById("tab3");
				var s3 = document.getElementsByName("zhicaidan3").length;
				if(s3 <= 4){
					$(tr3).after("<tr name='zhicaidan3'><td><input type='tel' value='31' /></td><td>——子：<input type='text'/></td><td><select onchange='getValue1()' id='zhu1'><option value='0'>请选择</option><option value='1'>发送信息</option><option value='2'>跳转图文信息页</option><option value='3'>跳转链接</option></select></td><td><a href='html/wenben.html' id='zhu1_1' style='display: none;'><input type='text' style='border: none;' placeholder='点击编辑文本信息'/></a><a href='edit.html' id='zhu1_2' style='display: none;'><input value='点击编辑图文信息' type='button'/></a><input type='text' id='zhu1_3' style='display: none;' placeholder='请输入链接地址' /></td><td><input type='checkbox' /></td><td><a href='#'>删除</a></td></tr>");
				}
			}*/
	/*		function show_zi1(){
				var i = 0;
				
				if(i=1){
					document.getElementById("z1_zi1").style.display='table-row';
				}
			}
			*/
			window.onload=function(){    
   				onload1();    
   				onload2();    
   				onload3();
            //获取table中的数据======================================================================
                var oChuli=document.getElementsByClassName("chuli")[0];
                var oInput=oChuli.getElementsByTagName("input");
                var oDiv=document.getElementById("add_input");
                //保存
				oInput[0].onclick=function () {
                    //第一组tr中的值
                    var oTrobj=document.getElementsByTagName("table")[0].getElementsByTagName("tr");
                    var totalarr=new Array();
                    var num=0;
                    for(var i=1;i<oTrobj.length;i++){
                        var buttonarr=new Array();
                        if(oTrobj[i].style.display!="none" ){
                        	var oTdobj=oTrobj[i].getElementsByTagName("td");
                            var ordernum=oTdobj[0].firstChild.value;
                            var name=oTdobj[1].getElementsByTagName("input")[0].value;
                            buttonarr['name']=name;
                            if(name !=""){
                               // buttonarr['ordernum']=ordernum;
                                buttonarr['name']=name;
                                //多选框中的值
                                var oSelect=oTdobj[2].firstChild;
                                var index=oSelect.selectedIndex;
                                var optionValue=oSelect.options[index].value;
                                var type=getname(optionValue);
                                buttonarr['type']=type;
                                //响应动作的值
                                var responseval=getVal(optionValue,oTdobj[3]);
                                for(var key in responseval){
                                    buttonarr[key]=responseval[key];
                                }
                                totalarr[num]=buttonarr;
                                num++;
							}

                        }
                    }
                    //主菜单的拼接
					//子菜单的拼接:只有name和sub_button
					var str = "{\"button\":"+"[";
                    for(key1 in totalarr){
                    	str += "{";
                        for(key2 in totalarr[key1]){
                            str += "\"" + key2 +"\":\""+totalarr[key1][key2]+"\",";
                        }
                        str = str.substr(0,str.length-1);
						str += "},"
                    }
                    str = str.substr(0,str.length-1);
                    str += "]}";

                    $.ajax({
						"url":"addmenu.php",
						"type":"post",
						//"data":{"button":[{"name":"hxq","pwd":"123"},{"name":"dd","pwd":"88"}]},
						"data":JSON.parse(str),
						"dataType":"json",
						 success:function (res) {
							//console.log(res);
							if(res==1){
								alert("保存成功");
							}else{
								alert("保存失败");

							}

                        }
                    });

                };

			};
			//获取触发动作对应的type类型
			function getname(val) {
              var typename="";
              if(val==1){
                  typename='click';

			  }else if(val==2){
              	typename='view_limited';
			  }else if(val==3){
			  	typename="view";
			  }else{
				  typename="";
			  }
			  return  typename;
            }
            //获取响应动作的内容
			function  getVal(val,object) {
                var  arr = new Array();
                switch (val){
                    case "1":
                        var oZhu1_1=object.getElementsByTagName("textarea")[0];
                        arr['key']=oZhu1_1.value;
                        break;
					case "2":
                        var oZhu1_2=object.getElementsByTagName("input")[0];
                        arr['media_id']="";
                        break;
					case "3":
                        var oZhu1_3=object.getElementsByTagName("input")[1];
                        arr['url']=oZhu1_3.value;
                        break;
					default:
                        arr="";
                        break;
				}
                return  arr;
            }
			function onload1(){
			 	 var a = 0;
			 	 document.getElementById("add1").onclick = function(){
			 	 	a++;
			 	 	if(a == 1){
			 	 		document.getElementById("z1_zi1").style.display='table-row';
			 	 	}
			 	 	if(a == 2){
			 	 		document.getElementById("z1_zi2").style.display='table-row';
			 	 	}
			 	 	if(a == 3){
			 	 		document.getElementById("z1_zi3").style.display='table-row';
			 	 	}
			 	 	if(a == 4){
			 	 		document.getElementById("z1_zi4").style.display='table-row';
			 	 	}
			 	 	if(a == 5){
			 	 		document.getElementById("z1_zi5").style.display='table-row';
			 	 	}
			 	 }
			 }
			function onload2(){
			 	 var b = 0;
			 	 document.getElementById("add2").onclick = function(){
			 	 	b++;
			 	 	if(b == 1){
			 	 		document.getElementById("z2_zi1").style.display='table-row';
			 	 	}
			 	 	if(b == 2){
			 	 		document.getElementById("z2_zi2").style.display='table-row';
			 	 	}
			 	 	if(b == 3){
			 	 		document.getElementById("z2_zi3").style.display='table-row';
			 	 	}
			 	 	if(b == 4){
			 	 		document.getElementById("z2_zi4").style.display='table-row';
			 	 	}
			 	 	if(b == 5){
			 	 		document.getElementById("z2_zi5").style.display='table-row';
			 	 	}
			 	 }
			 }
			function onload3(){
			 	 var c = 0;
			 	 document.getElementById("add3").onclick = function(){
			 	 	c++;
			 	 	if(c == 1){
			 	 		document.getElementById("z3_zi1").style.display='table-row';
			 	 	}
			 	 	if(c == 2){
			 	 		document.getElementById("z3_zi2").style.display='table-row';
			 	 	}
			 	 	if(c == 3){
			 	 		document.getElementById("z3_zi3").style.display='table-row';
			 	 	}
			 	 	if(c == 4){
			 	 		document.getElementById("z3_zi4").style.display='table-row';
			 	 	}
			 	 	if(c == 5){
			 	 		document.getElementById("z3_zi5").style.display='table-row';
			 	 	}
			 	 }
			 }
			function getValue1(){
				var objs = document.getElementById("zhu1");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_1").show();
					$("#zhu1_2").hide();
					$("#zhu1_3").hide();
				}
				if(val==2){
					$("#zhu1_2").show();
					$("#zhu1_1").hide();
					$("#zhu1_3").hide();
				}
				if(val==3){
					$("#zhu1_3").show();
					$("#zhu1_1").hide();
					$("#zhu1_2").hide();
				}
				if(val==0){
					$("#zhu1_1").hide();
					$("#zhu1_2").hide();
					$("#zhu1_3").hide();
				}
			}
			function getValue2(){
				var objs = document.getElementById("zhu2");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_1").show();
					$("#zhu2_2").hide();
					$("#zhu2_3").hide();
				}
				if(val==2){
					$("#zhu2_2").show();
					$("#zhu2_1").hide();
					$("#zhu2_3").hide();
				}
				if(val==3){
					$("#zhu2_3").show();
					$("#zhu2_1").hide();
					$("#zhu2_2").hide();
				}
				if(val==0){
					$("#zhu2_3").hide();
					$("#zhu2_1").hide();
					$("#zhu2_2").hide();
				}
			}
			function getValue3(){
				var objs = document.getElementById("zhu3");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_1").show();
					$("#zhu3_2").hide();
					$("#zhu3_3").hide();
				}
				if(val==2){
					$("#zhu3_2").show();
					$("#zhu3_1").hide();
					$("#zhu3_3").hide();
				}
				if(val==3){
					$("#zhu3_3").show();
					$("#zhu3_1").hide();
					$("#zhu3_2").hide();
				}
				if(val==0){
					$("#zhu3_3").hide();
					$("#zhu3_1").hide();
					$("#zhu3_2").hide();
				}
			}
			function getValue11(){
				var objs = document.getElementById("zhu1_zi1");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_zi1_1").show();
					$("#zhu1_zi1_2").hide();
					$("#zhu1_zi1_3").hide();
				}
				if(val==2){
					$("#zhu1_zi1_2").show();
					$("#zhu1_zi1_1").hide();
					$("#zhu1_zi1_3").hide();
				}
				if(val==3){
					$("#zhu1_zi1_3").show();
					$("#zhu1_zi1_1").hide();
					$("#zhu1_zi1_2").hide();
				}
				if(val==0){
					$("#zhu1_zi1_3").hide();
					$("#zhu1_zi1_1").hide();
					$("#zhu1_zi1_2").hide();
				}
			}
			function getValue12(){
				var objs = document.getElementById("zhu1_zi2");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_zi2_1").show();
					$("#zhu1_zi2_2").hide();
					$("#zhu1_zi2_3").hide();
				}
				if(val==2){
					$("#zhu1_zi2_2").show();
					$("#zhu1_zi2_1").hide();
					$("#zhu1_zi2_3").hide();
				}
				if(val==3){
					$("#zhu1_zi2_3").show();
					$("#zhu1_zi2_1").hide();
					$("#zhu1_zi2_2").hide();
				}
				if(val==0){
					$("#zhu1_zi2_3").hide();
					$("#zhu1_zi2_1").hide();
					$("#zhu1_zi2_2").hide();
				}
			}
			function getValue13(){
				var objs = document.getElementById("zhu1_zi3");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_zi3_1").show();
					$("#zhu1_zi3_2").hide();
					$("#zhu1_zi3_3").hide();
				}
				if(val==2){
					$("#zhu1_zi3_2").show();
					$("#zhu1_zi3_1").hide();
					$("#zhu1_zi3_3").hide();
				}
				if(val==3){
					$("#zhu1_zi3_3").show();
					$("#zhu1_zi3_1").hide();
					$("#zhu1_zi3_2").hide();
				}
				if(val==0){
					$("#zhu1_zi3_3").hide();
					$("#zhu1_zi3_1").hide();
					$("#zhu1_zi3_2").hide();
				}
			}
			function getValue14(){
				var objs = document.getElementById("zhu1_zi4");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_zi4_1").show();
					$("#zhu1_zi4_2").hide();
					$("#zhu1_zi4_3").hide();
				}
				if(val==2){
					$("#zhu1_zi4_2").show();
					$("#zhu1_zi4_1").hide();
					$("#zhu1_zi4_3").hide();
				}
				if(val==3){
					$("#zhu1_zi4_3").show();
					$("#zhu1_zi4_1").hide();
					$("#zhu1_zi4_2").hide();
				}
				if(val==0){
					$("#zhu1_zi4_3").hide();
					$("#zhu1_zi4_1").hide();
					$("#zhu1_zi4_2").hide();
				}
			}
			function getValue15(){
				var objs = document.getElementById("zhu1_zi5");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu1_zi5_1").show();
					$("#zhu1_zi5_2").hide();
					$("#zhu1_zi5_3").hide();
				}
				if(val==2){
					$("#zhu1_zi5_2").show();
					$("#zhu1_zi5_1").hide();
					$("#zhu1_zi5_3").hide();
				}
				if(val==3){
					$("#zhu1_zi5_3").show();
					$("#zhu1_zi5_1").hide();
					$("#zhu1_zi5_2").hide();
				}
				if(val==0){
					$("#zhu1_zi5_3").hide();
					$("#zhu1_zi5_1").hide();
					$("#zhu1_zi5_2").hide();
				}
			}
			function getValue21(){
				var objs = document.getElementById("zhu2_zi1");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_zi1_1").show();
					$("#zhu2_zi1_2").hide();
					$("#zhu2_zi1_3").hide();
				}
				if(val==2){
					$("#zhu2_zi1_2").show();
					$("#zhu2_zi1_1").hide();
					$("#zhu2_zi1_3").hide();
				}
				if(val==3){
					$("#zhu2_zi1_3").show();
					$("#zhu2_zi1_1").hide();
					$("#zhu2_zi1_2").hide();
				}
				if(val==0){
					$("#zhu2_zi1_3").hide();
					$("#zhu2_zi1_1").hide();
					$("#zhu2_zi1_2").hide();
				}
			}
			function getValue22(){
				var objs = document.getElementById("zhu2_zi2");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_zi2_1").show();
					$("#zhu2_zi2_2").hide();
					$("#zhu2_zi2_3").hide();
				}
				if(val==2){
					$("#zhu2_zi2_2").show();
					$("#zhu2_zi2_1").hide();
					$("#zhu2_zi2_3").hide();
				}
				if(val==3){
					$("#zhu2_zi2_3").show();
					$("#zhu2_zi2_1").hide();
					$("#zhu2_zi2_2").hide();
				}
				if(val==0){
					$("#zhu2_zi2_3").hide();
					$("#zhu2_zi2_1").hide();
					$("#zhu2_zi2_2").hide();
				}
			}
			function getValue23(){
				var objs = document.getElementById("zhu2_zi3");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_zi3_1").show();
					$("#zhu2_zi3_2").hide();
					$("#zhu2_zi3_3").hide();
				}
				if(val==2){
					$("#zhu2_zi3_2").show();
					$("#zhu2_zi3_1").hide();
					$("#zhu2_zi3_3").hide();
				}
				if(val==3){
					$("#zhu2_zi3_3").show();
					$("#zhu2_zi3_1").hide();
					$("#zhu2_zi3_2").hide();
				}
				if(val==0){
					$("#zhu2_zi3_3").hide();
					$("#zhu2_zi3_1").hide();
					$("#zhu2_zi3_2").hide();
				}
			}
			function getValue24(){
				var objs = document.getElementById("zhu2_zi4");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_zi4_1").show();
					$("#zhu2_zi4_2").hide();
					$("#zhu2_zi4_3").hide();
				}
				if(val==2){
					$("#zhu2_zi4_2").show();
					$("#zhu2_zi4_1").hide();
					$("#zhu2_zi4_3").hide();
				}
				if(val==3){
					$("#zhu2_zi4_3").show();
					$("#zhu2_zi4_1").hide();
					$("#zhu2_zi4_2").hide();
				}
				if(val==0){
					$("#zhu2_zi4_3").hide();
					$("#zhu2_zi4_1").hide();
					$("#zhu2_zi4_2").hide();
				}
			}
			function getValue25(){
				var objs = document.getElementById("zhu2_zi5");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu2_zi5_1").show();
					$("#zhu2_zi5_2").hide();
					$("#zhu2_zi5_3").hide();
				}
				if(val==2){
					$("#zhu2_zi5_2").show();
					$("#zhu2_zi5_1").hide();
					$("#zhu2_zi5_3").hide();
				}
				if(val==3){
					$("#zhu2_zi5_3").show();
					$("#zhu2_zi5_1").hide();
					$("#zhu2_zi5_2").hide();
				}
				if(val==0){
					$("#zhu2_zi5_3").hide();
					$("#zhu2_zi5_1").hide();
					$("#zhu2_zi5_2").hide();
				}
			}
			function getValue31(){
				var objs = document.getElementById("zhu3_zi1");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_zi1_1").show();
					$("#zhu3_zi1_2").hide();
					$("#zhu3_zi1_3").hide();
				}
				if(val==2){
					$("#zhu3_zi1_2").show();
					$("#zhu3_zi1_1").hide();
					$("#zhu3_zi1_3").hide();
				}
				if(val==3){
					$("#zhu3_zi1_3").show();
					$("#zhu3_zi1_1").hide();
					$("#zhu3_zi1_2").hide();
				}
				if(val==0){
					$("#zhu3_zi1_3").hide();
					$("#zhu3_zi1_1").hide();
					$("#zhu3_zi1_2").hide();
				}
			}
			function getValue32(){
				var objs = document.getElementById("zhu3_zi2");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_zi2_1").show();
					$("#zhu3_zi2_2").hide();
					$("#zhu3_zi2_3").hide();
				}
				if(val==2){
					$("#zhu3_zi2_2").show();
					$("#zhu3_zi2_1").hide();
					$("#zhu3_zi2_3").hide();
				}
				if(val==3){
					$("#zhu3_zi2_3").show();
					$("#zhu3_zi2_1").hide();
					$("#zhu3_zi2_2").hide();
				}
				if(val==0){
					$("#zhu3_zi2_3").hide();
					$("#zhu3_zi2_1").hide();
					$("#zhu3_zi2_2").hide();
				}
			}
			function getValue33(){
				var objs = document.getElementById("zhu3_zi3");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_zi3_1").show();
					$("#zhu3_zi3_2").hide();
					$("#zhu3_zi3_3").hide();
				}
				if(val==2){
					$("#zhu3_zi3_2").show();
					$("#zhu3_zi3_1").hide();
					$("#zhu3_zi3_3").hide();
				}
				if(val==3){
					$("#zhu3_zi3_3").show();
					$("#zhu3_zi3_1").hide();
					$("#zhu3_zi3_2").hide();
				}
				if(val==0){
					$("#zhu3_zi3_3").hide();
					$("#zhu3_zi3_1").hide();
					$("#zhu3_zi3_2").hide();
				}
			}
			function getValue34(){
				var objs = document.getElementById("zhu3_zi4");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_zi4_1").show();
					$("#zhu3_zi4_2").hide();
					$("#zhu3_zi4_3").hide();
				}
				if(val==2){
					$("#zhu3_zi4_2").show();
					$("#zhu3_zi4_1").hide();
					$("#zhu3_zi4_3").hide();
				}
				if(val==3){
					$("#zhu3_zi4_3").show();
					$("#zhu3_zi4_1").hide();
					$("#zhu3_zi4_2").hide();
				}
				if(val==0){
					$("#zhu3_zi4_3").hide();
					$("#zhu3_zi4_1").hide();
					$("#zhu3_zi4_2").hide();
				}
			}
			function getValue35(){
				var objs = document.getElementById("zhu3_zi5");
				var val = objs.options[objs.selectedIndex].value;
				if(val==1){
					$("#zhu3_zi5_1").show();
					$("#zhu3_zi5_2").hide();
					$("#zhu3_zi5_3").hide();
				}
				if(val==2){
					$("#zhu3_zi5_2").show();
					$("#zhu3_zi5_1").hide();
					$("#zhu3_zi5_3").hide();
				}
				if(val==3){
					$("#zhu3_zi5_3").show();
					$("#zhu3_zi5_1").hide();
					$("#zhu3_zi5_2").hide();
				}
				if(val==0){
					$("#zhu3_zi5_3").hide();
					$("#zhu3_zi5_1").hide();
					$("#zhu3_zi5_2").hide();
				}
			}
