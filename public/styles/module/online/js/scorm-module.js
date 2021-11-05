mod_scorm_launch_next_sco = null;
mod_scorm_launch_prev_sco = null;
mod_scorm_activate_item = null;
mod_scorm_parse_toc_tree = null;
scorm_layout_widget = null;
window.scorm_current_node = null;

function underscore(str) {
    str = String(str).replace(/.N/g, ".");
    return str.replace(/\./g, "__")
}

M.mod_scorm = {};
M.mod_scorm.init = function (Y, nav_display, navposition_left, navposition_top, hide_toc, collapsetocwinsize, toc_title, window_name, launch_sco, scoes_nav) {
    var scorm_disable_toc = !1;
    var scorm_hide_nav = !0;
    var scorm_hide_toc = !0;
    if (hide_toc == 0) {
        if (nav_display !== 0) {
            scorm_hide_nav = !1
        }
        scorm_hide_toc = !1
    }
    else if (hide_toc == 3) {
        scorm_disable_toc = !0
    }
    scoes_nav = Y.JSON.parse(scoes_nav);
    var scorm_buttons = [];
    var scorm_bloody_labelclick = !1;
    var scorm_nav_panel;
    Y.use('button', 'dd-plugin', 'panel', 'resize', 'gallery-sm-treeview', function (Y) {
        Y.TreeView.prototype.getNodeByAttribute = function (attribute, value) {
            var node = null, domnode = Y.one('a[' + attribute + '="' + value + '"]');
            if (domnode !== null) {
                node = scorm_tree_node.getNodeById(domnode.ancestor('li').get('id'))
            }
            return node
        };
        Y.TreeView.prototype.openAll = function () {
            this.get('container').all('.yui3-treeview-can-have-children').each(function (target) {
                this.getNodeById(target.get('id')).open()
            }, this)
        };
        Y.TreeView.prototype.closeAll = function () {
            this.get('container').all('.yui3-treeview-can-have-children').each(function (target) {
                this.getNodeById(target.get('id')).close()
            }, this)
        }
        var scorm_parse_toc_tree = function (srcNode) {
            var SELECTORS = {child: '> li', label: '> li, > a', textlabel: '> li, > span', subtree: '> ul, > li'},
                children = [];
            srcNode.all(SELECTORS.child).each(function (childNode) {
                var child = {}, labelNode = childNode.one(SELECTORS.label),
                    textNode = childNode.one(SELECTORS.textlabel), subTreeNode = childNode.one(SELECTORS.subtree);
                if (labelNode) {
                    var title = labelNode.getAttribute('title');
                    var scoid = labelNode.getData('scoid');
                    child.label = labelNode.get('outerHTML');
                    if (title && title !== '#') {
                        child.title = title
                    }
                    if (typeof scoid !== 'undefined') {
                        child.scoid = scoid
                    }
                }
                else if (textNode) {
                    child.label = textNode.get('outerHTML')
                }
                if (subTreeNode) {
                    child.children = scorm_parse_toc_tree(subTreeNode)
                }
                children.push(child)
            });
            return children
        };
        mod_scorm_parse_toc_tree = scorm_parse_toc_tree;
        var scorm_activate_item = function (node) {
            if (!node) {
                return
            }
            var content = Y.one('#scorm_content');
            var old = Y.one('#scorm_object');
            if (old) {
                var scorm_active_url = Y.one('#scorm_object').getAttribute('src');
                var node_full_url = 'mod/scorm/loadSCO.php?' + node.title;
                if (node_full_url === scorm_active_url) {
                    return
                }
                if (!window_name) {
                    content.removeChild(old);
                    old = null
                }
            }
            scorm_current_node = node;
            if (!scorm_current_node.state.selected) {
                scorm_current_node.select()
            }
            scorm_tree_node.closeAll();

            var url_prefix = '/online/scorm/redirect?';
            var el_old_api = document.getElementById('scormapi123');
            if (el_old_api) {
                el_old_api.parentNode.removeChild(el_old_api)
            }
            var obj = document.createElement('iframe');
            obj.setAttribute('id', 'scorm_object');
            obj.setAttribute('type', 'text/html');
            obj.setAttribute('allowfullscreen', 'allowfullscreen');
            obj.setAttribute('webkitallowfullscreen', 'webkitallowfullscreen');
            obj.setAttribute('mozallowfullscreen', 'mozallowfullscreen');
            if (!window_name && node.title != null) {
                obj.setAttribute('src', url_prefix + node.title)
            }
            if (window_name) {
                var mine = window.open('', '', 'width=1,height=1,left=0,top=0,scrollbars=no');
                if (!mine) {
                    alert(M.util.get_string('popupsblocked', 'scorm'))
                }
                mine.close()
            }
            if (old) {
                if (window_name) {
                    var cwidth = scormplayerdata.cwidth;
                    var cheight = scormplayerdata.cheight;
                    var poptions = scormplayerdata.popupoptions;
                    poptions = poptions + ',resizable=yes';
                    //scorm_openpopup(M.cfg.wwwroot + "/mod/scorm/loadSCO.php?" + node.title, window_name, poptions, cwidth, cheight)
                }
            }
            else {
                content.prepend(obj)
            }
            if (scorm_hide_nav == !1) {
                if (nav_display === 1 && navposition_left > 0 && navposition_top > 0) {
                    Y.one('#scorm_object').addClass(cssclasses.scorm_nav_under_content)
                }
                scorm_fixnav()
            }
            scorm_tree_node.openAll()
        };
        mod_scorm_activate_item = scorm_activate_item;

        var scorm_fixnav = function () {
            var skipprevnode = scorm_skipprev(scorm_current_node);
            var prevnode = scorm_prev(scorm_current_node);
            var skipnextnode = scorm_skipnext(scorm_current_node);
            var nextnode = scorm_next(scorm_current_node);
            var upnode = scorm_up(scorm_current_node);
            scorm_buttons[0].set('disabled', ((skipprevnode === null) || (typeof (skipprevnode.scoid) === 'undefined') || (scoes_nav[skipprevnode.scoid].isvisible === "false") || (skipprevnode.title === null) || (scoes_nav[launch_sco].hideprevious === 1)));
            scorm_buttons[1].set('disabled', ((prevnode === null) || (typeof (prevnode.scoid) === 'undefined') || (scoes_nav[prevnode.scoid].isvisible === "false") || (prevnode.title === null) || (scoes_nav[launch_sco].hideprevious === 1)));
            scorm_buttons[2].set('disabled', (upnode === null) || (typeof (upnode.scoid) === 'undefined') || (scoes_nav[upnode.scoid].isvisible === "false") || (upnode.title === null));
            scorm_buttons[3].set('disabled', ((nextnode === null) || ((nextnode.title === null) && (scoes_nav[launch_sco].flow !== 1)) || (typeof (nextnode.scoid) === 'undefined') || (scoes_nav[nextnode.scoid].isvisible === "false") || (scoes_nav[launch_sco].hidecontinue === 1)));
            scorm_buttons[4].set('disabled', ((skipnextnode === null) || (skipnextnode.title === null) || (typeof (skipnextnode.scoid) === 'undefined') || (scoes_nav[skipnextnode.scoid].isvisible === "false") || scoes_nav[launch_sco].hidecontinue === 1))
        };
        var scorm_toggle_toc = function (windowresize) {
            var toc = Y.one('#scorm_toc');
            var scorm_content = Y.one('#scorm_content');
            var scorm_toc_toggle_btn = Y.one('#scorm_toc_toggle_btn');
            var toc_disabled = toc.hasClass('disabled');
            var disabled_by = toc.getAttribute('disabled-by');
            toc.setStyle('width', null);
            scorm_content.setStyle('width', null);
            if (windowresize === !0) {
                if (disabled_by === 'user') {
                    return
                }
                var body = Y.one('body');
                if (body.get('winWidth') < collapsetocwinsize) {
                    toc.addClass(cssclasses.disabled).setAttribute('disabled-by', 'screen-size');
                    scorm_toc_toggle_btn.setHTML('&gt;').set('title', M.util.get_string('show', 'moodle'));
                    scorm_content.removeClass(cssclasses.scorm_grid_content_toc_visible).addClass(cssclasses.scorm_grid_content_toc_hidden)
                }
                else if (body.get('winWidth') > collapsetocwinsize) {
                    toc.removeClass(cssclasses.disabled).removeAttribute('disabled-by');
                    scorm_toc_toggle_btn.setHTML('&lt;').set('title', M.util.get_string('hide', 'moodle'));
                    scorm_content.removeClass(cssclasses.scorm_grid_content_toc_hidden).addClass(cssclasses.scorm_grid_content_toc_visible)
                }
                return
            }
            if (toc_disabled) {
                toc.removeClass(cssclasses.disabled).removeAttribute('disabled-by');
                scorm_toc_toggle_btn.setHTML('&lt;').set('title', M.util.get_string('hide', 'moodle'));
                scorm_content.removeClass(cssclasses.scorm_grid_content_toc_hidden).addClass(cssclasses.scorm_grid_content_toc_visible)
            }
            else {
                toc.addClass(cssclasses.disabled).setAttribute('disabled-by', 'user');
                scorm_toc_toggle_btn.setHTML('&gt;').set('title', M.util.get_string('show', 'moodle'));
                scorm_content.removeClass(cssclasses.scorm_grid_content_toc_visible).addClass(cssclasses.scorm_grid_content_toc_hidden)
            }
        };
        var scorm_resize_layout = function () {
            if (window_name) {
                return
            }
            var scorm_toc_node = Y.one('#scorm_toc');
            var maxwidth = parseInt(Y.one('#scorm_layout').getComputedStyle('width'), 10);
            scorm_toc_node.setStyle('maxWidth', (maxwidth - 200));
            var cwidth = parseInt(scorm_toc_node.getComputedStyle('width'), 10);
            if (cwidth > (maxwidth - 1)) {
                scorm_toc_node.setStyle('width', (maxwidth - 50))
            }
            var newheight = Y.one('body').get('winHeight') - 5 - Y.one('#scorm_layout').getY() - window.pageYOffset;
            if (newheight < 680 || isNaN(newheight)) {
                newheight = 680
            }
            Y.one('#scorm_layout').setStyle('height', newheight)
        };
        var scorm_ajax_request = function (url, datastring) {
            var myRequest = NewHttpReq();
            var result = DoRequest(myRequest, url + datastring);
            return result
        };
        var scorm_up = function (node, update_launch_sco) {
            if (node.parent && node.parent.parent && typeof scoes_nav[launch_sco].parentscoid !== 'undefined') {
                var parentscoid = scoes_nav[launch_sco].parentscoid;
                var parent = node.parent;
                if (parent.title !== scoes_nav[parentscoid].url) {
                    parent = scorm_tree_node.getNodeByAttribute('title', scoes_nav[parentscoid].url);
                    if (parent === null) {
                        parent = scorm_tree_node.rootNode.children[0];
                        parent.title = scoes_nav[parentscoid].url
                    }
                }
                if (update_launch_sco) {
                    launch_sco = parentscoid
                }
                return parent
            }
            return null
        };
        var scorm_lastchild = function (node) {
            if (node.children.length) {
                return scorm_lastchild(node.children[node.children.length - 1])
            }
            else {
                return node
            }
        };
        var scorm_prev = function (node, update_launch_sco) {
            if (node.previous() && node.previous().children.length && typeof scoes_nav[launch_sco].prevscoid !== 'undefined') {
                node = scorm_lastchild(node.previous());
                if (node) {
                    var prevscoid = scoes_nav[launch_sco].prevscoid;
                    if (node.title !== scoes_nav[prevscoid].url) {
                        node = scorm_tree_node.getNodeByAttribute('title', scoes_nav[prevscoid].url);
                        if (node === null) {
                            node = scorm_tree_node.rootNode.children[0];
                            node.title = scoes_nav[prevscoid].url
                        }
                    }
                    if (update_launch_sco) {
                        launch_sco = prevscoid
                    }
                    return node
                }
                else {
                    return null
                }
            }
            return scorm_skipprev(node, update_launch_sco)
        };
        var scorm_skipprev = function (node, update_launch_sco) {
            if (node.previous() && typeof scoes_nav[launch_sco].prevsibling !== 'undefined') {
                var prevsibling = scoes_nav[launch_sco].prevsibling;
                var previous = node.previous();
                var prevscoid = scoes_nav[launch_sco].prevscoid;
                if (previous.title !== scoes_nav[prevscoid].url) {
                    previous = scorm_tree_node.getNodeByAttribute('title', scoes_nav[prevsibling].url);
                    if (previous === null) {
                        previous = scorm_tree_node.rootNode.children[0];
                        previous.title = scoes_nav[prevsibling].url
                    }
                }
                if (update_launch_sco) {
                    launch_sco = prevsibling
                }
                return previous
            }
            else if (node.parent && node.parent.parent && typeof scoes_nav[launch_sco].parentscoid !== 'undefined') {
                var parentscoid = scoes_nav[launch_sco].parentscoid;
                var parent = node.parent;
                if (parent.title !== scoes_nav[parentscoid].url) {
                    parent = scorm_tree_node.getNodeByAttribute('title', scoes_nav[parentscoid].url);
                    if (parent === null) {
                        parent = scorm_tree_node.rootNode.children[0];
                        parent.title = scoes_nav[parentscoid].url
                    }
                }
                if (update_launch_sco) {
                    launch_sco = parentscoid
                }
                return parent
            }
            return null
        };
        var scorm_next = function (node, update_launch_sco) {
            if (node === !1) {
                return scorm_tree_node.children[0]
            }
            if (node.children.length && typeof scoes_nav[launch_sco].nextscoid != 'undefined') {
                node = node.children[0];
                var nextscoid = scoes_nav[launch_sco].nextscoid;
                if (node.title !== scoes_nav[nextscoid].url) {
                    node = scorm_tree_node.getNodeByAttribute('title', scoes_nav[nextscoid].url);
                    if (node === null) {
                        node = scorm_tree_node.rootNode.children[0];
                        node.title = scoes_nav[nextscoid].url
                    }
                }
                if (update_launch_sco) {
                    launch_sco = nextscoid
                }
                return node
            }
            return scorm_skipnext(node, update_launch_sco)
        };
        var scorm_skipnext = function (node, update_launch_sco) {
            var next = node.next();
            if (next && next.title && typeof scoes_nav[launch_sco] !== 'undefined' && typeof scoes_nav[launch_sco].nextsibling !== 'undefined') {
                var nextsibling = scoes_nav[launch_sco].nextsibling;
                if (next.title !== scoes_nav[nextsibling].url) {
                    next = scorm_tree_node.getNodeByAttribute('title', scoes_nav[nextsibling].url);
                    if (next === null) {
                        next = scorm_tree_node.rootNode.children[0];
                        next.title = scoes_nav[nextsibling].url
                    }
                }
                if (update_launch_sco) {
                    launch_sco = nextsibling
                }
                return next
            }
            else if (node.parent && node.parent.parent && typeof scoes_nav[launch_sco].parentscoid !== 'undefined') {
                var parentscoid = scoes_nav[launch_sco].parentscoid;
                var parent = node.parent;
                if (parent.title !== scoes_nav[parentscoid].url) {
                    parent = scorm_tree_node.getNodeByAttribute('title', scoes_nav[parentscoid].url);
                    if (parent === null) {
                        parent = scorm_tree_node.rootNode.children[0]
                    }
                }
                if (update_launch_sco) {
                    launch_sco = parentscoid
                }
                return scorm_skipnext(parent, update_launch_sco)
            }
            return null
        };
        var scorm_launch_prev_sco = function () {
            var result = null;
            if (scoes_nav[launch_sco].flow === 1) {
                var datastring = scoes_nav[launch_sco].url + '&function=scorm_seq_flow&request=backward';
                result = scorm_ajax_request(M.cfg.wwwroot + '/mod/scorm/datamodels/sequencinghandler.php?', datastring);
                mod_scorm_seq = encodeURIComponent(result);
                result = Y.JSON.parse(result);
                if (typeof result.nextactivity.id != undefined) {
                    var node = scorm_prev(scorm_tree_node.getSelectedNodes()[0]);
                    if (node == null) {
                        node = scorm_tree_node.getSelectedNodes()[0]
                    }
                    if (node.title !== scoes_nav[result.nextactivity.id].url) {
                        node = scorm_tree_node.getNodeByAttribute('title', scoes_nav[result.nextactivity.id].url);
                        if (node === null) {
                            node = scorm_tree_node.rootNode.children[0];
                            node.title = scoes_nav[result.nextactivity.id].url
                        }
                    }
                    launch_sco = result.nextactivity.id;
                    scorm_activate_item(node);
                    scorm_fixnav()
                }
                else {
                    scorm_activate_item(scorm_prev(scorm_tree_node.getSelectedNodes()[0], !0))
                }
            }
            else {
                scorm_activate_item(scorm_prev(scorm_tree_node.getSelectedNodes()[0], !0))
            }
        };
        var scorm_launch_next_sco = function () {
            var result = null;
            if (scoes_nav[launch_sco].flow === 1) {
                var datastring = scoes_nav[launch_sco].url + '&function=scorm_seq_flow&request=forward';
                result = scorm_ajax_request(M.cfg.wwwroot + '/mod/scorm/datamodels/sequencinghandler.php?', datastring);
                mod_scorm_seq = encodeURIComponent(result);
                result = Y.JSON.parse(result);
                if (typeof result.nextactivity !== 'undefined' && typeof result.nextactivity.id !== 'undefined') {
                    var node = scorm_next(scorm_tree_node.getSelectedNodes()[0]);
                    if (node === null) {
                        node = scorm_tree_node.getSelectedNodes()[0]
                    }
                    node = scorm_tree_node.getNodeByAttribute('title', scoes_nav[result.nextactivity.id].url);
                    if (node === null) {
                        node = scorm_tree_node.rootNode.children[0];
                        node.title = scoes_nav[result.nextactivity.id].url
                    }
                    launch_sco = result.nextactivity.id;
                    scorm_activate_item(node);
                    scorm_fixnav()
                }
                else {
                    scorm_activate_item(scorm_next(scorm_tree_node.getSelectedNodes()[0], !0))
                }
            }
            else {
                scorm_activate_item(scorm_next(scorm_tree_node.getSelectedNodes()[0], !0))
            }
        };
        mod_scorm_launch_prev_sco = scorm_launch_prev_sco;
        mod_scorm_launch_next_sco = scorm_launch_next_sco;
        var cssclasses = {
            scorm_grid_content_toc_hidden: 'yui3-u-1',
            scorm_grid_toc: 'yui3-u-1-5',
            scorm_grid_toggle: 'yui3-u-1-24',
            scorm_grid_content_toc_visible: 'yui3-u-3-4',
            scorm_nav_under_content: 'scorm_nav_under_content',
            disabled: 'disabled'
        };
        Y.one('#scorm_toc_title').setHTML(toc_title);
        if (scorm_disable_toc) {
            Y.one('#scorm_toc').addClass(cssclasses.disabled);
            Y.one('#scorm_toc_toggle').addClass(cssclasses.disabled);
            Y.one('#scorm_content').addClass(cssclasses.scorm_grid_content_toc_hidden)
        }
        else {
            Y.one('#scorm_toc').addClass(cssclasses.scorm_grid_toc);
            Y.one('#scorm_toc_toggle').addClass(cssclasses.scorm_grid_toggle);
            Y.one('#scorm_toc_toggle_btn').setHTML('&lt;').setAttribute('title', M.util.get_string('hide', 'moodle'));
            Y.one('#scorm_content').addClass(cssclasses.scorm_grid_content_toc_visible);
            scorm_toggle_toc(!0)
        }
        if (!scorm_disable_toc) {
            if (scorm_hide_toc == !0) {
                Y.one('#scorm_toc').addClass(cssclasses.disabled);
                Y.one('#scorm_toc_toggle_btn').setHTML('&gt;').setAttribute('title', M.util.get_string('show', 'moodle'));
                Y.one('#scorm_content').removeClass(cssclasses.scorm_grid_content_toc_visible).addClass(cssclasses.scorm_grid_content_toc_hidden)
            }
        }
        Y.one('#scorm_toc').removeClass('loading');
        Y.one('#scorm_toc_toggle').removeClass('loading');
        var layout_width = parseInt(Y.one('#scorm_layout').getComputedStyle('width'), 10);
        var scorm_resize_handle = new Y.Resize({node: '#scorm_toc', handles: 'r', defMinWidth: 0.2 * layout_width});
        var toc_source = Y.one('#scorm_tree > ul');
        var toc = scorm_parse_toc_tree(toc_source);
        var el = document.getElementById('scorm_tree');
        el.innerHTML = '';
        var tree = new Y.TreeView({container: '#scorm_tree', nodes: toc, multiSelect: !1});
        scorm_tree_node = tree;

        tree.after('select', function (e) {
            var node = e.node;
            if (node.title == '' || node.title == null) {
                return
            }
            if (obj = Y.one('#scorm_object')) {
                var scorm_active_url = obj.getAttribute('src');
                var node_full_url = '/mod/scorm/loadSCO.php?' + node.title;
                if (node_full_url === scorm_active_url) {
                    return
                }
            }
            else if (scorm_current_node == node) {
                return
            }
            if (typeof node.scoid !== 'undefined') {
                launch_sco = node.scoid
            }
            scorm_activate_item(node);
            if (node.children.length) {
                scorm_bloody_labelclick = !0
            }
        });
        if (!scorm_disable_toc) {
            tree.on('close', function (e) {
                if (scorm_bloody_labelclick) {
                    scorm_bloody_labelclick = !1;
                    return !1
                }
            });
            tree.subscribe('open', function (e) {
                if (scorm_bloody_labelclick) {
                    scorm_bloody_labelclick = !1;
                    return !1
                }
            })
        }
        tree.render();
        tree.openAll();
        Y.one(Y.config.win).on('focus', function (e) {
            var current = scorm_tree_node.getSelectedNodes()[0];
            var toc_disabled = Y.one('#scorm_toc').hasClass('disabled');
            if (current.id && !toc_disabled) {
                Y.one('#' + current.id).focus()
            }
        });
        if (scorm_hide_nav == !1) {
            var navbuttonshtml = '<span id="scorm_nav"><button id="nav_skipprev">&lt;&lt;</button>&nbsp;' + '<button id="nav_prev">&lt;</button>&nbsp;<button id="nav_up">^</button>&nbsp;' + '<button id="nav_next">&gt;</button>&nbsp;<button id="nav_skipnext">&gt;&gt;</button></span>';
            if (nav_display === 1) {
                Y.one('#scorm_navpanel').setHTML(navbuttonshtml)
            }
            else {
                var navposition = null;
                if (navposition_left < 0 && navposition_top < 0) {
                    navposition = Y.one('#scorm_toc').getXY();
                    navposition[1] += 200
                }
                else {
                    navposition = [];
                    navposition[0] = parseInt(navposition_left, 10);
                    navposition[1] = parseInt(navposition_top, 10)
                }
                scorm_nav_panel = new Y.Panel({
                    fillHeight: "body",
                    headerContent: M.util.get_string('navigation', 'scorm'),
                    visible: !0,
                    xy: navposition,
                    zIndex: 999
                });
                scorm_nav_panel.set('bodyContent', navbuttonshtml);
                scorm_nav_panel.removeButton('close');
                scorm_nav_panel.plug(Y.Plugin.Drag, {handles: ['.yui3-widget-hd']});
                scorm_nav_panel.render()
            }
            scorm_buttons[0] = new Y.Button({
                srcNode: '#nav_skipprev', render: !0, on: {
                    'click': function (ev) {
                        scorm_activate_item(scorm_skipprev(scorm_tree_node.getSelectedNodes()[0], !0))
                    }, 'keydown': function (ev) {
                        if (ev.domEvent.keyCode === 13 || ev.domEvent.keyCode === 32) {
                            scorm_activate_item(scorm_skipprev(scorm_tree_node.getSelectedNodes()[0], !0))
                        }
                    }
                }
            });
            scorm_buttons[1] = new Y.Button({
                srcNode: '#nav_prev', render: !0, on: {
                    'click': function (ev) {
                        scorm_launch_prev_sco()
                    }, 'keydown': function (ev) {
                        if (ev.domEvent.keyCode === 13 || ev.domEvent.keyCode === 32) {
                            scorm_launch_prev_sco()
                        }
                    }
                }
            });
            scorm_buttons[2] = new Y.Button({
                srcNode: '#nav_up', render: !0, on: {
                    'click': function (ev) {
                        scorm_activate_item(scorm_up(scorm_tree_node.getSelectedNodes()[0], !0))
                    }, 'keydown': function (ev) {
                        if (ev.domEvent.keyCode === 13 || ev.domEvent.keyCode === 32) {
                            scorm_activate_item(scorm_up(scorm_tree_node.getSelectedNodes()[0], !0))
                        }
                    }
                }
            });
            scorm_buttons[3] = new Y.Button({
                srcNode: '#nav_next', render: !0, on: {
                    'click': function (ev) {
                        scorm_launch_next_sco()
                    }, 'keydown': function (ev) {
                        if (ev.domEvent.keyCode === 13 || ev.domEvent.keyCode === 32) {
                            scorm_launch_next_sco()
                        }
                    }
                }
            });
            scorm_buttons[4] = new Y.Button({
                srcNode: '#nav_skipnext', render: !0, on: {
                    'click': function (ev) {
                        scorm_activate_item(scorm_skipnext(scorm_tree_node.getSelectedNodes()[0], !0))
                    }, 'keydown': function (ev) {
                        if (ev.domEvent.keyCode === 13 || ev.domEvent.keyCode === 32) {
                            scorm_activate_item(scorm_skipnext(scorm_tree_node.getSelectedNodes()[0], !0))
                        }
                    }
                }
            })
        }
        var scorm_first_url = null;

        if (typeof tree.rootNode.children[0] !== 'undefined') {
            if (tree.rootNode.children[0].title !== scoes_nav[launch_sco].url) {
                var node = tree.getNodeByAttribute('title', scoes_nav[launch_sco].url);
                if (node !== null) {
                    scorm_first_url = node
                }
            }
            else {
                scorm_first_url = tree.rootNode.children[0]
            }
        }
        if (scorm_first_url == null) {
            scorm_first_url = tree.rootNode
        }
        scorm_first_url.title = scoes_nav[launch_sco].url;
        scorm_activate_item(scorm_first_url);
        scorm_resize_layout();
        Y.one('#scorm_toc_toggle').on('click', scorm_toggle_toc);
        Y.one('#scorm_toc_toggle').on('key', scorm_toggle_toc, 'down:enter,32');
        Y.on("windowresize", function () {
            scorm_resize_layout();
            var toc_displayed = Y.one('#scorm_toc').getComputedStyle('display') !== 'none';
            if ((!scorm_disable_toc && !scorm_hide_toc) || toc_displayed) {
                scorm_toggle_toc(!0)
            }
            var layout_width = parseInt(Y.one('#scorm_layout').getComputedStyle('width'), 10);
            scorm_resize_handle.set('defMinWidth', 0.2 * layout_width)
        });
        scorm_resize_handle.on('resize:resize', function () {
            var tocwidth = parseInt(Y.one('#scorm_toc').getComputedStyle('width'), 10);
            var layoutwidth = parseInt(Y.one('#scorm_layout').getStyle('width'), 10);
            Y.one('#scorm_content').setStyle('width', (layoutwidth - tocwidth - 60))
        })
    })
};
M.mod_scorm.connectPrereqCallback = {
    success: function (id, o) {
        if (o.responseText !== undefined) {
            var snode = null, stitle = null;
            if (scorm_tree_node && o.responseText) {
                snode = scorm_tree_node.getSelectedNodes()[0];
                stitle = null;
                if (snode) {
                    stitle = snode.title
                }
                scorm_tree_node.clear(scorm_tree_node.createNode())
            }
            var el_old_tree = document.getElementById('scormtree123');
            if (el_old_tree) {
                el_old_tree.parentNode.removeChild(el_old_tree)
            }
            var el_new_tree = document.createElement('div');
            var pagecontent = document.getElementById("page-content");
            if (!pagecontent) {
                pagecontent = document.getElementById("content")
            }
            el_new_tree.setAttribute('id', 'scormtree123');
            el_new_tree.innerHTML = o.responseText;
            el_new_tree.style.display = 'none';
            pagecontent.appendChild(el_new_tree);
            var startNode = el_new_tree.firstChild.firstChild;
            if (typeof startNode != "undefined" && startNode.tagName == 'LI') {
                startNode = el_new_tree
            }
            var toc_source = Y.one('#scormtree123 > ul');
            var toc = mod_scorm_parse_toc_tree(toc_source);
            scorm_tree_node.appendNode(scorm_tree_node.rootNode, toc);
            var el = document.getElementById('scormtree123');
            el.parentNode.removeChild(el);
            scorm_tree_node.render();
            scorm_tree_node.openAll();
            if (stitle !== null) {
                snode = scorm_tree_node.getNodeByAttribute('title', stitle);
                if (snode && !snode.state.destroyed) {
                    snode.select();
                    var toc_disabled = Y.one('#scorm_toc').hasClass('disabled');
                    if (!toc_disabled) {
                        if (!snode.state.selected) {
                            snode.select()
                        }
                    }
                }
            }
        }
    }, failure: function (id, o) {
    }
}