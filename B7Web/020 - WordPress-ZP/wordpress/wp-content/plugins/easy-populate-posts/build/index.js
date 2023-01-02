const maxTax = document.getElementById('spp_max_tax');const maxTaxList = document.getElementById('spp-max-tax-listing');const maxMeta = document.getElementById('spp_max_meta');const maxMetaList = document.getElementById('spp-max-meta-listing');const sppMakePost = (actionName,targetElement) =>{const frm = document.getElementById('spp_settings_frm');if (frm && targetElement){const arg = new FormData(frm);arg.append('action',actionName);targetElement.classList.add('processing');fetch(sppSettings.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',},mode:'cors',cache:'no-cache',credentials:'same-origin',body:new URLSearchParams(arg),}).then((response) =>{return response.text();}).then((data) =>{targetElement.textContent = data;targetElement.innerHTML = data;targetElement.dataset.processing = 0;targetElement.classList.remove('processing');}).catch(() =>{targetElement.dataset.processing = 0;targetElement.classList.remove('processing');});}};if (maxTax && maxTaxList){maxTax.addEventListener('change',() =>{sppMakePost('spp_max_tax_listing',maxTaxList);});}if (maxMeta && maxMetaList){maxMeta.addEventListener('change',() =>{sppMakePost('spp_max_meta_listing',maxMetaList);});}const sppSaveSettings = (saveButton) =>{const targetEl = document.getElementById('spp_settings_wrap');const imagesList = document.getElementById('spp_images_list');if (targetEl){if (1 == targetEl.dataset.processing || 1 == targetEl.dataset.stopped ){return;}targetEl.textContent = sppSettings.beginImages;targetEl.innerHTML = sppSettings.beginImages;targetEl.dataset.processing = 1;}saveButton.innerHTML = sppSettings.messages.settings.init;saveButton.setAttribute('disabled',true);saveButton.classList.add('processing');const frm = document.getElementById('spp_settings_frm');const arg = new FormData(frm);arg.append('action','spp_save_settings');frm.classList.add('processing');var object ={};arg.forEach((value,key) =>object[key] = value);var spp = JSON.parse(JSON.stringify(object));const windowReload = spp['spp_groups[load]'] ? true:false;fetch(sppSettings.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',},mode:'cors',cache:'no-cache',credentials:'same-origin',body:new URLSearchParams(arg),}).then((response) =>{return response.text();}).then((data) =>{targetEl.textContent = data;targetEl.innerHTML = data;targetEl.dataset.processing = 0;saveButton.innerHTML = sppSettings.messages.settings.done;saveButton.removeAttribute('disabled');setTimeout(function (){saveButton.innerHTML = sppSettings.messages.settings.ready;saveButton.classList.remove('processing');if (imagesList){imagesList.value = '';}initFigures(saveButton);sppLoadGroups(saveButton,windowReload,spp);frm.classList.remove('processing');},800);}).catch(() =>{targetEl.dataset.processing = 0;frm.classList.remove('processing');saveButton.classList.remove('processing');});};const sppExecuteSettings = (executeButton) =>{const targetEl = document.getElementById('spp_populate_wrap');if (targetEl){if (1 == targetEl.dataset.processing || 1 == targetEl.dataset.stopped ){return;}targetEl.dataset.processing = 1;}executeButton.innerHTML = sppSettings.messages.populate.init;executeButton.setAttribute('disabled',true);executeButton.classList.add('processing');const frm = document.getElementById('spp_settings_frm');const arg = new FormData(frm);arg.append('action','spp_populate');frm.classList.add('processing');fetch(sppSettings.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',},mode:'cors',cache:'no-cache',credentials:'same-origin',body:new URLSearchParams(arg),}).then((response) =>{return response.text();}).then((data) =>{targetEl.textContent = data;targetEl.innerHTML = data;targetEl.dataset.processing = 0;executeButton.innerHTML = sppSettings.messages.populate.done;executeButton.removeAttribute('disabled');frm.classList.remove('processing');executeButton.classList.remove('processing');setTimeout(function (){executeButton.innerHTML = sppSettings.messages.populate.ready;const lastCounter = document.getElementById('spp_latest_counter');const counter = document.getElementById('spp_start_counter');if (lastCounter && counter){counter.value = parseInt(lastCounter.value);}},800);}).catch(() =>{targetEl.dataset.processing = 0;frm.classList.remove('processing');executeButton.classList.remove('processing');});};const sppTestPattern = (patternButton) =>{const targetEl = document.getElementById('spp_pattern_test');if (targetEl){if (1 == targetEl.dataset.processing || 1 == targetEl.dataset.stopped ){return;}targetEl.dataset.processing = 1;}patternButton.setAttribute('disabled',true);patternButton.classList.add('processing');const arg = new FormData();arg.append('sample',document.getElementById('spp_pattern_sample').value);arg.append('action','spp_pattern_test');targetEl.classList.add('processing');fetch(sppSettings.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',},mode:'cors',cache:'no-cache',credentials:'same-origin',body:new URLSearchParams(arg),}).then((response) =>{return response.text();}).then((data) =>{targetEl.textContent = data;targetEl.innerHTML = data;targetEl.dataset.processing = 0;patternButton.removeAttribute('disabled');patternButton.classList.remove('processing');targetEl.classList.remove('processing');}).catch(() =>{targetEl.dataset.processing = 0;targetEl.classList.remove('processing');patternButton.classList.remove('processing');});};function sppGroupAction(groupId,actionType){const saveButton = document.getElementById('spp_save');if (!saveButton || !actionType){return;}if (actionType !== 'import' && !groupId){return;}const elTitle = document.getElementById('spp_groups_add_title');if (elTitle){elTitle.value = '';}const targetEl = document.getElementById(`spp_groups_${actionType}`);if (targetEl){let proceed = true;if (actionType === 'discard'){proceed = confirm(sppSettings.discardGroup);}if (proceed){if (actionType === 'import'){sppSaveSettings(saveButton);}else{targetEl.value = groupId;if (actionType !== 'export'){sppSaveSettings(saveButton);}else{sppLoadGroups(saveButton,false,{groupId,actionType});}}}}}const sppLoadGroups = (patternButton,windowReload,extra) =>{const targetEl = document.getElementById('spp_groups_list');if (targetEl){if (1 == targetEl.dataset.processing || 1 == targetEl.dataset.stopped ){return;}targetEl.dataset.processing = 1;}patternButton.setAttribute('disabled',true);patternButton.classList.add('processing');const arg = new FormData();arg.append('action','spp_groups_list');if (extra.actionType){arg.append('actionType',extra.actionType);}if (extra.groupId){arg.append('groupId',extra.groupId);}targetEl.classList.add('processing');fetch(sppSettings.ajaxUrl,{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8',},mode:'cors',cache:'no-cache',credentials:'same-origin',body:new URLSearchParams(arg),}).then((response) =>{return response.text();}).then((data) =>{targetEl.textContent = data;targetEl.innerHTML = data;targetEl.dataset.processing = 0;patternButton.removeAttribute('disabled');patternButton.classList.remove('processing');targetEl.classList.remove('processing');if (windowReload){location.reload();}}).catch(() =>{targetEl.dataset.processing = 0;targetEl.classList.remove('processing');patternButton.classList.remove('processing');});};function sppToggleHint(sel){const item = document.querySelector(sel);if (item){if (item.classList.contains('not-visible')){item.classList.remove('not-visible');}else{item.classList.add('not-visible');}}}function sppApplyPattern(val){const tester = document.getElementById('spp_pattern_sample');const button = document.getElementById('spp_pattern_button');if (tester){tester.value = val;button.click();}}const sppAssessCounter = () =>{const prefix = document.getElementById('spp_title_prefix');if (prefix){const prefixTitle = document.getElementById('spp_title_prefix_elem');const theTitle = document.getElementById('spp_title_elem');const counter = document.getElementById('spp_start_counter');const counterPrefix = document.getElementById('spp_title_prefix_counter');if (prefix.value.indexOf('#[')>= 0){prefixTitle.classList.add('not-visible');theTitle.classList.remove('not-visible');}else{prefixTitle.classList.remove('not-visible');theTitle.classList.add('not-visible');}if (prefix.value.indexOf('#NO') < 0){counter.setAttribute('disabled',true);counterPrefix.style.display = 'none';}else{counterPrefix.style.display = 'flex';counter.removeAttribute('disabled');}}};const sppDeleteImage = (saveButton,id) =>{const delValue = document.getElementById('spp_del');if (delValue){delValue.value = id;sppSaveSettings(saveButton);delValue.value = '';}};function sppCopyImagesList(){const list = document.getElementById('spp_images_list');const initial = document.getElementById('spp_initial_images');if (list && initial){list.value = initial.innerHTML;}}const toggleCleanup = () =>{const item = document.getElementById('spp-will-cleanup');if (item){if (item.classList.contains('spp-will-cleanup')){item.classList.remove('spp-will-cleanup');}else{item.classList.add('spp-will-cleanup');}}};const sppInit = () =>{const saveButton = document.getElementById('spp_save');if (saveButton){saveButton.addEventListener('click',(event) =>{event.preventDefault();event.stopPropagation();sppSaveSettings(saveButton);});}const saveLikeButtons = document.querySelectorAll('.save-settings-alt');if (saveLikeButtons){saveLikeButtons.forEach((item) =>{item.addEventListener('click',(event) =>{event.preventDefault();event.stopPropagation();sppSaveSettings(saveButton);});});}const patternButton = document.getElementById('spp_pattern_button');if (patternButton){patternButton.addEventListener('click',(event) =>{event.preventDefault();event.stopPropagation();sppTestPattern(patternButton);});}const prefix = document.getElementById('spp_title_prefix');if (prefix){sppAssessCounter();prefix.addEventListener('change',() =>{sppAssessCounter();});}const contentType = document.getElementById('spp_content_type');if (contentType){const contentTypeP = document.getElementById('spp-content-p-wrap');const contentTypeG = document.getElementById('spp-content-g-wrap');contentType.addEventListener('change',() =>{if (parseInt(contentType.value) === 3){contentTypeP.setAttribute('style','display:none');contentTypeG.setAttribute('style','display:block');}else{contentTypeP.setAttribute('style','display:block');contentTypeG.setAttribute('style','display:none');}});}const gutenberg = document.getElementById('spp_gutenberg_block');const dropCap = document.getElementById('spp_gutenberg_drop_cap_wrap');const dropCapInput = document.getElementById('spp_gutenberg_drop_cap');if (gutenberg && dropCap){gutenberg.addEventListener('change',() =>{if (gutenberg.checked){dropCap.style.display = 'block';}else{dropCap.style.display = 'none';dropCapInput.removeAttribute('checked');}});}const executeButton = document.getElementById('spp_execute');if (executeButton){executeButton.addEventListener('click',(event) =>{event.preventDefault();event.stopPropagation();sppExecuteSettings(executeButton);});}const dateType = document.getElementById('spp_date_type');if (dateType){dateType.addEventListener('change',() =>{let item = '';for (let i = 0;i <= 3;i++){item = document.getElementById('spp_random_date_text' + i);if (item){if (i === parseInt(dateType.value)){item.style.display = 'block';}else{item.style.display = 'none';}}}item = document.getElementById('spp_specific_date_wrap');if (item){if (3 === parseInt(dateType.value)){item.style.display = 'block';}else{item.style.display = 'none';}}});}initFigures(saveButton);};const initFigures = (saveButton) =>{const figureButtons = document.querySelectorAll('.spp_figure .dashicons-no');if (figureButtons){for (let i = 0;i < figureButtons.length;i++){figureButtons[ i ].addEventListener('click',() =>{sppDeleteImage(saveButton,figureButtons[ i ].dataset.id);});}}};sppInit();