document.addEventListener("DOMContentLoaded", function (event) {
	var PGBlockPostGrid = document.querySelectorAll(".PGBlockPostGrid");
	if (PGBlockPostGrid != null) {
		PGBlockPostGrid.forEach((item) => {
			var postgridargs = item.getAttribute("postgridargs");

			var postgridargsObj = JSON.parse(postgridargs);
			var blockId = postgridargsObj.blockId;
			var lazyLoad = postgridargsObj.lazyLoad;
			var lazyLoadEnable = lazyLoad.enable;
			if (lazyLoadEnable == "yes") {
				var lazyloadWrap = document.querySelector("#lazyload-" + blockId);
				item.style.display = "block";
				lazyloadWrap.style.display = "none";
			}
		});
	}

	function isInViewport(el) {
		const rect = el.getBoundingClientRect();
		return (
			rect.top >= 0 &&
			rect.left >= 0 &&
			rect.bottom <=
				(window.innerHeight || document.documentElement.clientHeight) &&
			rect.right <= (window.innerWidth || document.documentElement.clientWidth)
		);
	}

	var loader = document.querySelector(".infinite-loader");

	if (loader != null) {
		document.addEventListener(
			"scroll",
			function () {
				var isInView = isInViewport(loader);
				var loading = loader.getAttribute("loading");

				if (isInView) {
					loader.classList.add("visible");
					if (loading == null) {
						loadPostsOnScroll();
					}
					//loadPostsOnScroll();
				} else {
					loader.classList.remove("visible");
				}
			},
			{
				passive: true,
			}
		);
	}

	function loadPostsOnScroll() {
		loader.setAttribute("loading", "true");

		var loaderParent = loader.closest("div[blockArgs]");
		var blockargs = loaderParent.getAttribute("blockargs");

		var blockargsObj = blockargs != null ? JSON.parse(blockargs) : {};
		var blockId =
			blockargsObj.blockId != undefined ? blockargsObj.blockId : null;
		if (blockId == null) return;

		var noPosts = blockargsObj.noPosts;

		if (noPosts == true) return;

		var queryArgs = post_grid_vars[blockId].queryArgs;
		var rawData = post_grid_vars[blockId].layout.rawData;

		var pagination = blockargsObj.pagination;
		var loadMoreText = pagination.loadMoreText;
		var noMorePosts = pagination.noMorePosts;

		var loadingText = pagination.loadingText;
		var loadingIcon = pagination.loadingIcon;
		var page = pagination.page;

		loader.innerHTML = loadingIcon + loadingText;

		setTimeout(() => {
			//loader.innerHTML = loadingText;

			var queryArgsX = queryArgs.map((x) => {
				if (x.id == "paged") {
					x.val = page + 1;
					blockargsObj.pagination.page = page + 1;
				}

				return x;
			});

			loaderParent.setAttribute("blockargs", JSON.stringify(blockargsObj));

			let data = {
				queryArgs: queryArgsX,
				rawData: rawData,
			};

			fetch(post_grid_vars["siteUrl"] + "/wp-json/post-grid/v2/get_posts", {
				method: "POST",
				body: JSON.stringify(data),
				headers: {
					"Content-Type": "application/json;charset=utf-8",
				},
			})
				.then((response) => {
					if (response.ok && response.status < 400) {
						response.json().then((dataX) => {
							var posts = dataX["posts"] != undefined ? dataX["posts"] : [];
							var noPosts =
								dataX["noPosts"] != undefined ? dataX["noPosts"] : false;

							if (noPosts == true) {
								loader.innerHTML = noMorePosts;
								blockargsObj.noPosts = true;
								loaderParent.setAttribute(
									"blockargs",
									JSON.stringify(blockargsObj)
								);

								return;
							}

							if (!noPosts) {
								var html = "";

								posts.map((x) => {
									html += '<div className="item">' + x.html + "</div>";
								});

								itemsLoopWrap.insertAdjacentHTML("beforeend", html);
								//loader.scrollIntoView({ behavior: "smooth", block: "start", inline: "nearest" });
							}

							loader.removeAttribute("loading");
						});
					}
				})
				.catch((_error) => {
					//this.saveAsStatus = 'error';
					// handle the error
				});
		}, 500);
	}

	const loadMorewrap = document.querySelector(".loadmore .page-numbers");
	const itemsLoopWrap = document.querySelector(".items-loop");
	const loopLoadingWrap = document.querySelector(".loop-loading");

	var paginationAjax = document.querySelector(".pagination.ajax");


	if (paginationAjax != null) {
		new MutationObserver(function (mutationsList, observer) {
			for (const mutation of mutationsList) {
				if (mutation.type === "childList") {
					ajaxPageClick();
				}
			}
		}).observe(paginationAjax, { childList: true, subtree: true });
	}

	function ajaxPageClick() {
		var paginationAjaxLink = document.querySelectorAll(".pagination.ajax a");

		if (paginationAjaxLink != null) {
			paginationAjaxLink.forEach((el) =>
				el.addEventListener("click", (event) => {
					event.preventDefault();
					var href = event.target.getAttribute("href");

					var hrefParts = href.split("/");
					var filtered = hrefParts.filter(function (el) {
						return el;
					});
					var lastElement = parseInt(filtered.pop());

					var blockargs = el
						.closest("div[blockArgs]")
						.getAttribute("blockargs");

					var blockargsObj = blockargs != null ? JSON.parse(blockargs) : {};
					var blockId =
						blockargsObj.blockId != undefined ? blockargsObj.blockId : null;

					if (blockId == null) return;

					var queryArgs = post_grid_vars[blockId].queryArgs;
					var rawData = post_grid_vars[blockId].layout.rawData;

					var pagination = blockargsObj.pagination;
					var loadMoreText = pagination.loadMoreText;
					var prevText = pagination.prevText;
					var nextText = pagination.nextText;
					var maxPageNum = parseInt(pagination.maxPageNum);

					var loadingText = pagination.loadingText;
					var loadingIcon = pagination.loadingIcon;

					var queryArgsX = queryArgs.map((x) => {
						if (x.id == "paged") {
							x.val = lastElement;
						}

						return x;
					});

					let data = {
						queryArgs: queryArgsX,
						prevText: prevText,
						nextText: nextText,
						maxPageNum: maxPageNum,

						rawData: rawData,
					};


					if (loopLoadingWrap != null) {
						loopLoadingWrap.innerHTML = loadingIcon + loadingText;
					}

					fetch(post_grid_vars["siteUrl"] + "/wp-json/post-grid/v2/get_posts", {
						method: "POST",
						body: JSON.stringify(data),
						headers: {
							"Content-Type": "application/json;charset=utf-8",
						},
					})
						.then((response) => {
							if (response.ok && response.status < 400) {
								response.json().then((dataX) => {
									var posts = dataX["posts"] != undefined ? dataX["posts"] : [];
									var pagination =
										dataX["pagination"] != undefined ? dataX["pagination"] : [];

									var html = "";
									var paginationHtml = "";

									posts.map((x) => {
										html += '<div className="item">' + x.html + "</div>";
									});

									pagination.map((x) => {
										paginationHtml += x;
									});

									loopLoadingWrap.innerHTML = "";
									itemsLoopWrap.innerHTML = html;

									paginationAjax.innerHTML = paginationHtml;

									setTimeout(() => {
										itemsLoopWrap.scrollIntoView({
											behavior: "smooth",
											block: "start",
											inline: "nearest",
										});
										setTimeout(() => {
											window.scrollBy(0, -30);
										}, 500);
									}, 500);
								});
							}
						})
						.catch((_error) => {
							//this.saveAsStatus = 'error';
							// handle the error
						});
				})
			);
		}
	}

	ajaxPageClick();

	loadMorewrap != null &&
		loadMorewrap.addEventListener("click", function () {
			var disabled = loadMorewrap.getAttribute("disabled");

			if (disabled == "disabled") return;

			var loadMorewrapparent = loadMorewrap.closest("div[blockArgs]");
			var blockargs = loadMorewrapparent.getAttribute("blockargs");

			// const blockargs = loadMorewrap.getAttribute('blockargs');
			var blockargsObj = blockargs != null ? JSON.parse(blockargs) : {};
			var blockId =
				blockargsObj.blockId != undefined ? blockargsObj.blockId : null;
			if (blockId == null) return;

			var queryArgs = post_grid_vars[blockId].queryArgs;
			var rawData = post_grid_vars[blockId].layout.rawData;



			var pagination = blockargsObj.pagination;
			var loadMoreText = pagination.loadMoreText;
			var noMorePosts = pagination.noMorePosts;

			var loadingText = pagination.loadingText;
			var loadingIcon = pagination.loadingIcon;
			var loadMoreIcon = pagination.loadMoreIcon;
			var loadMorePosition = pagination.loadMorePosition;
			var loadingPosition = pagination.loadingPosition;
			
			var page = pagination.page;

			var noPosts = blockargsObj.noPosts;

			if (loadingPosition == "beforeText") {
				loadMorewrap.innerHTML = loadingIcon + " " + loadingText;
			}
			if (loadingPosition == "afterText") {
				loadMorewrap.innerHTML = loadingText + " " + loadingIcon;
			}

			setTimeout(() => {
				// loadMorewrap.innerHTML = loadMoreText;

				var queryArgsX = queryArgs.map((x) => {
					if (x.id == "paged") {
						x.val = page + 1;
						blockargsObj.pagination.page = page + 1;
					}

					return x;
				});

				loadMorewrapparent.setAttribute(
					"blockargs",
					JSON.stringify(blockargsObj)
				);

				let data = {
					queryArgs: queryArgsX,
					rawData: rawData,
				};

				fetch(post_grid_vars["siteUrl"] + "/wp-json/post-grid/v2/get_posts", {
					method: "POST",
					body: JSON.stringify(data),
					headers: {
						"Content-Type": "application/json;charset=utf-8",
					},
				})
					.then((response) => {
						if (response.ok && response.status < 400) {
							response.json().then((dataX) => {
								var posts = dataX["posts"] != undefined ? dataX["posts"] : [];
								var noPosts =
									dataX["noPosts"] != undefined ? dataX["noPosts"] : false;

							

								if (!noPosts) {
									var html = "";

									posts.map((x) => {
										html += '<div className="item">' + x.html + "</div>";
									});

									itemsLoopWrap.insertAdjacentHTML("beforeend", html);
									loadMorewrap.scrollIntoView({
										behavior: "smooth",
										block: "start",
										inline: "nearest",
									});
								}

								if (noPosts) {
									loadMorewrap.innerHTML = noMorePosts;
									loadMorewrap.setAttribute("disabled", "disabled");
								}

								if (loadMorePosition == "beforeText") {
									loadMorewrap.innerHTML = loadMoreIcon + " " + loadMoreText;
								}
								if (loadMorePosition == "afterText") {
									loadMorewrap.innerHTML = loadMoreText + " " + loadMoreIcon;
								}
							});
						}
					})
					.catch((_error) => {
						//this.saveAsStatus = 'error';
						// handle the error
					});
			}, 500);
		});
});

