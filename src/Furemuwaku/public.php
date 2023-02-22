<!DOCTYPE html>
<html data-theme="light">
	<head>
		
		<title> GGarden · <?= $title ?> </title>
		
		<meta charset="UTF-8" />
		<meta name="author" content="Ari" />
		<meta name="copyright" content="2022" />
		<meta name="robots" content="index, follow" />
		<meta name="theme-color" content="#028174" />
		<meta name="robots" content="noimageindex, noarchive" />
		<meta name="description" content="" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		
		<!-- Boxicons -->
		<link rel="stylesheet" href="/assets/icons/boxicons/css/boxicons.min.css" />
		
		<!-- SwiperJS Style -->
		<link rel="stylesheet" href="/assets/scripts/swiper/swiper-bundle.min.css" />
		
		<!-- Builtin Style -->
		<link rel="stylesheet" href="/assets/styles/ggarden.css" />
		<link rel="stylesheet" href="/assets/styles/ggarden.style.css" />
		
		<?php if( ENVIRONMENT === DEVELOPMENT ) { ?>
		<!-- Mobile JavaScript Console -->
		<script type="text/javascript" src="/assets/scripts/eruda/eruda.js"></script>
		<script type="text/javascript">
			
			// Eruda Initialization.
			eruda.init();
			
		</script>
		<?php } ?>
		
		<!-- Application Data Shared -->
		<script type="text/javascript">
			
			// Data Application.
			const $data = <?= $data ?? "{}" ?>;
			
		</script>
		
		<!-- VueJS Module -->
		<script type="text/javascript" src="/assets/scripts/vue/dist/vue.global.js"></script>
		<script type="text/javascript" src="/assets/scripts/vuex/dist/vuex.global.js"></script>
		<script type="text/javascript" src="/assets/scripts/vue-router/dist/vue-router.global.js"></script>
		
		<!-- ChartJS Module -->
		<script type="text/javascript" src="/assets/scripts/chartjs/dist/chart.umd.js"></script>
		
		<!-- SwiperJS Module -->
		<script type="text/javascript" src="/assets/scripts/swiper/swiper-bundle.min.js"></script>
		
		<!-- Builtin Module Script -->
		<script type="text/javascript" src="/assets/scripts/ggarden.chart.js"></script>
		<script type="text/javascript" src="/assets/scripts/ggarden.swiper.js"></script>
		<script type="text/javascript" src="/assets/scripts/ggarden.js"></script>
		
		<!-- Web Icon -->
		<link rel="icon" href="/assets/images/android-chrome-192x192.png" sizes="192x192" />
		<link rel="icon" href="/assets/images/android-chrome-512x512.png" sizes="512x512" />
		<link rel="icon" href="/assets/images/favicon-32x32.png" sizes="32x32" />
		<link rel="icon" href="/assets/images/favicon-16x16.png" sizes="16x16" />
		
		<!-- Web Apple Icon -->
		<link rel="apple-touch-icon" href="/assets/images/apple-touch-icon.png" />
		
		<!-- Web Shortcut Icon -->
		<link rel="shortcut-icon" type="image/x-icon" href="/assets/images/favicon.ico" />
		
	</head>
	<body>
		
		<!-- Application Mounted -->
		<div id="root"></div>
		
		<!-- Builtin Script -->
		<script type="text/javascript" src="/assets/scripts/ggarden.index.js"></script>
		<script type="text/javascript">
			
			/*
			 * Routing path to be registered.
			 *
			 */
			const $Routes = [
				
				/*
				 * Home page.
				 *
				 * @except False
				 */
				{
					path: "/",
					name: "home",
					icon: [
						"bx-home",
						"bxs-home"
					],
					meta: {
						auth: false
					},
					slot: "Home",
					except: false,
					component: $HomeComponent
				},
				
				/*
				 * Signin
				 *
				 * @except True
				 */
				{
					path: "/signin",
					name: "signin",
					meta: {
						auth: false
					},
					except: true,
					children: [
						
						/*
						 * Signin Forgot Username
						 *
						 */
						{
							path: "forgot/username",
							name: "signin-fusername",
							meta: {
								auth: false
							},
							except: true,
							children: [
								
								/*
								 * Signin Verify Password
								 *
								 */
								{
									path: "verify",
									name: "signin-verify-pasw",
									meta: {
										auth: false
									},
									except: true,
									component: {}
								}
							],
							component: {}
						},
						
						/*
						 * Signin Forgot Password
						 *
						 */
						{
							path: "forgot/password",
							name: "signin-fpassword",
							except: true,
							children: [
								
								/*
								 * Signin Verify Code
								 *
								 */
								{
									path: "verify",
									name: "signin-verify-code",
									meta: {
										auth: false
									},
									except: true,
									component: {}
								}
							],
							component: {}
						},
						
						/*
						 * Signin Session
						 *
						 * @except True
						 */
						{
							path: "session",
							name: "signin-session",
							meta: {
								auth: false
							},
							except: true,
							component: {}
						}
					],
					component: $SigninComponent
				},
				
				/*
				 * User Signup
				 *
				 * @except True
				 */
				{
					path: "/signup",
					name: "signup",
					meta: {
						auth: false
					},
					except: true,
					component: $SignupComponent
				},
				
				/*
				 * Explore Articels
				 *
				 * @except False
				 */
				{
					path: "/explore",
					name: "explore",
					icon: [
						"bxs-dashboard",
						"bxs-dashboard"
					],
					meta: {
						auth: false
					},
					slot: "Explore",
					except: false,
					component: $ExploreComponent
				},
				
				/*
				 * Search Articel or User.
				 *
				 * @except False
				 */
				{
					path: "/search",
					name: "search",
					icon: [
						"bx-search",
						"bxs-search"
					],
					meta: {
						auth: false
					},
					slot: "Search",
					except: false,
					component: $SearchComponent
				},
				
				/*
				 * Create Articel Page
				 *
				 * @except True
				 */
				{
					path: "/create",
					name: "create",
					meta: {
						auth: true
					},
					except: true,
					component: $CreateComponent
				},
				
				/*
				 * About Page
				 *
				 * @except False
				 */
				{
					path: "/about",
					name: "about",
					icon: [
						"bx-info-circle",
						"bxs-info-circle"
					],
					meta: {
						auth: false
					},
					slot: "About",
					except: false,
					component: $AboutComponent
				},
				
				/*
				 * Contact Page
				 *
				 * @except False
				 */
				{
					path: "/contact",
					name: "contact",
					icon: [
						"bx-phone",
						"bxs-phone"
					],
					meta: {
						auth: false
					},
					slot: "Contact",
					except: false,
					component: $ContactComponent
				},
				
				/*
				 * Privacy Page
				 *
				 * @except False
				 */
				{
					path: "/privacy",
					name: "privacy",
					icon: [
						"bx-lock",
						"bxs-lock"
					],
					meta: {
						auth: false
					},
					slot: "Privacy",
					except: false,
					component: $PrivacyComponent
				},
				
				/*
				 * Sitemap Page
				 *
				 * @except False
				 */
				{
					path: "/sitemap",
					name: "sitemap",
					icon: [
						"bx-link",
						"bx-link-alt"
					],
					meta: {
						auth: false
					},
					slot: "Sitemap",
					except: false,
					component: $SitemapComponent
				},
				
				/*
				 * Articel Page
				 *
				 * @except True
				 */
				{
					path: "/p/:node",
					name: "plant",
					meta: {
						auth: false
					},
					except: true,
					children: [
						
						/*
						 * Articel Likes
						 *
						 * @except True
						 */
						{
							path: "likes",
							name: "plant-likes",
							meta: {
								auth: false
							},
							except: true,
							component: {}
						},
						
						/*
						 * Articel Views
						 *
						 * @except True
						 */
						{
							path: "views",
							name: "plant-views",
							meta: {
								auth: false
							},
							except: true,
							component: {}
						}
					],
					component: $PlantComponent
				},
				
				/*
				 * User Profile
				 *
				 * @name Profile
				 */
				{
					path: "/:username([a-zA-Z_\x80-\xff][a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{0,1})",
					name: "profile",
					meta: {
						auth: false
					},
					except: true,
					children: [
						
						/*
						 * User Profile Settings
						 *
						 * @except True
						 */
						{
							path: "settings",
							name: "profile-settings",
							except: true,
							component: {}
						}
					],
					component: $ProfileComponent,
				},
				
				/*
				 * Profile Refresh
				 *
				 * @name Profile Update
				 */
				{
					path: "/:username([a-zA-Z_\x80-\xff][a-zA-Z0-9_\.\x80-\xff]{0,}[a-zA-Z0-9_\x80-\xff]{0,1})/:node",
					name: "profile-updated",
					meta: {
						auth: true
					},
					except: true,
					component: {
						
						/*
						 * Called before the route that renders this component is confirmed.
						 * Does NOT have access to `this` component instance, because it has
						 * Not been created yet when this guard is called!
						 *
						 * @params Object $to
						 * @params Object $from
						 * @params Function $next
						 *
						 * @return Void
						 */
						beforeRouteEnter: function( to, from, next )
						{
							// Check if user has authenticated.
							if( $Authenticated() )
							{
								window.location.href = f( "/{}", $data.shared.profile.username );
							}
							else {
								window.location.href = "/signin";
							}
						}
					}
				},
				
				/*
				 * Not Found
				 *
				 * @except True
				 */
				{
					path: "/:error(.*?)",
					name: "error",
					meta: {},
					except: true,
					component: {
						methods: {
							
							/*
							 * Return if current route path is index.
							 *
							 * @return Boolean
							 */
							index: function()
							{
								return( this.$route.path.match( /index\.(html|php)$/ ) ? true : false );
							}
						},
						components: {
							Error: $Error,
							Home: $HomeComponent
						},
						template: `
							<div class="route-error">
								<Home v-if="index()" />
								<Error v-else>No such file or directory <b>{{ $route.path }}</b></Error>
							</div>
						`,
					}
				}
			];
			
			// The router instance.
			const $Router = VueRouter.createRouter({
				
				// Router history mode.
				history: VueRouter.createWebHistory(),
				
				// Define some routes.
				// Each route should map to a component.
				routes: $Routes,
				
				// Scroll behavior.
				scrollBehavior: ( to, from, save ) =>
				{
					// Check scroll has previous saved position.
					if( save )
					{
						return( save );
					}
					return({
						top: 0,
						behavior: "smooth"
					});
				}
			});
			
			( function( root, factory )
			{
				if( typeof define === "function" && define.amd )
				{
					// AMD. Register as an anonymous module.
					define( ['e'], function( e )
					{
						return( root.GGarden = factory( e ) );
					});
				}
				else if( typeof module === "object" && module.exports )
				{
					// Node. Does not work with strict CommonJS, but
					// only CommonJS-like environments that support module.exports,
					// like Node.
					module.exports = factory( require( "e" ) );
				}
				else {
					
					// Browser globals
					root.GGarden = factory( root.e );
				}
			}( typeof main !== "undefined" ? main : this, function( e )
			{
				// Enable strict mode.
				"use strict";
				
				// Add window onload event listener.
				window.addEventListener( "load", () =>
				{
					/*
					 * The application instance.
					 *
					 */
					const $App = Vue.createApp({
						data: () => ({
							avatar: {
								inject: {
									avatar: [
										"header-avatar"
									],
									wrapper: [
										"header-avatar-wrapper"
									]
								},
								title: "GGarden Avatar",
								route: "/",
								alt: "GGarden Avatar",
								src: "/assets/images/favicon.png"
							},
							date: new Date().getFullYear(),
							medsos: [
								{
									icon: "bx bxl-facebook",
									link: "https://facebook.com/hekisari"
								},
								{
									icon: "bx bxl-instagram",
									link: "https://instagram.com/hx.are"
								},
								{
									icon: "bx bxl-linkedin",
									link: "https://linkedin.com/in/hxari"
								},
								{
									icon: "bx bxl-twitter",
									link: "https://twitter.com/hxxAre"
								},
								{
									icon: "bx bxl-github",
									link: "https://github.com/hxAri"
								}
							],
							target: [
								{
									path: "/",
									slot: "Home"
								},
								{
									path: "/search",
									slot: "Search"
								},
								{
									path: "/about",
									slot: "About"
								},
								{
									path: "/privacy",
									slot: "Privacy"
								},
								{
									path: "/sitemap",
									slot: "Sitemap"
								},
							],
							profile: false,
							signed: false,
							toggle: false,
							shared: false
						}),
					
						/*
						 * Component computation.
						 *
						 * @values Object
						 */
						computed: {
							navbar: function()
							{
								return({
									template: f( "<div class=\"navbar-lists pd-14\">{}</div>", this.navbarBuilder( $Routes ) )
								});
							}
						},
						
						/*
						 * Component watcher.
						 *
						 * @values Object
						 */
						watch: {
						},
					
						/*
						 * Component mounted.
						 *
						 * @return Void
						 */
						mounted: function()
						{
							// Check if user is signed.
							if( $Authenticated() )
							{
								this.signed = true;
								this.profile = $data.shared.profile;
								this.profile.path = f( "/{}", this.profile.username );
							}
							this.shared = $data.shared;
						},
						methods: {
							navbarBuilder: function( routes, stack = "" )
							{
								routes.forEach( route => {
									
									// Check if route has name.
									if( is( route.except, Boolean ) && route.except )
									{
										return;
									}
									
									// Next building stack.
									stack += "<router-link to=\"{}\" class=\"navbar-router-link\">";
										stack += "<li class=\"list flex flex-left\">";
											stack += "<i class=\"bx {} mg-right-14 fs-20\"></i>{}";
										stack += "</li>";
									stack += "</router-link>";
									
									stack = f( stack, route.path, route.icon[( this.$route.path === route.path ? 1 : 0 )], route.slot );
								});
								return( stack );
							}
						},
						components: {
							Avatar: $AvatarComponent
						},
						template: `
							<div class="template">
								<div class="header">
									<div class="header-banner pd-14 flex flex-left">
										<Avatar :options="avatar" />
										<div class="header-buttons flex">
											<router-link :to="{ path: profile.path }" v-if="( signed && profile )">
												<button class="header-button button button-signin flex flex-center pd-8">
													<i class="bx bx-user fs-24"></i>
												</button>
											</router-link>
											<router-link to="/signup" class="mg-right-14" v-else>
												<button class="header-button button button-signin button-kw flex flex-center pd-8">Signup</button>
											</router-link>
											<button class="header-button button button-toggle flex flex-center pd-left-8 pd-right-8" @click="( toggle = true )">
												<i class="bx bx-menu fs-24"></i>
											</button>
										</div>
									</div>
								</div>
								<div class="header-break"></div>
								<div :class="[ 'navbar', toggle ? 'navbar-toggle' : '' ]">
									<div class="navbar-exit" @click="( toggle = false )"></div>
									<div :class="[ 'navbar-main', toggle ? 'navbar-toggle' : '' ]">
										<div class="navbar-header pd-14">
											<h4 class="header-title fb-45 mg-0">
												<router-link to="/">
													Green Garden
												</router-link>
											</h4>
										</div>
										<component v-bind:is="navbar"></component>
										<div class="navbar-bottom pd-14">
											<div v-if="( signed && profile )">
												<router-link class="mg-bottom-14" to="/create">
													<button class="navbar-button button button-pw flex flex-center pd-8 fb-45 mg-bottom-14">Create</button>
												</router-link>
												<router-link to="/logout">
													<button class="navbar-button button button-cc flex flex-center pd-8 fb-45 mg-bottom-14">Logout</button>
												</router-link>
											</div>
											<router-link to="/signin" v-else>
												<button class="navbar-button button button-kw flex flex-center pd-8 fb-45 mg-bottom-14">Signin</button>
											</router-link>
										</div>
									</div>
								</div>
								<div class="onview">
									<router-view />
								</div>
								<div class="footer flex flex-center">
									<div class="footer-wrapper">
										<div class="footer-content flex flex-center mg-mg-bottom-14">
											<div class="footer-group pd-14">
												<h5 class="footer-title mg-bottom-6">Pages</h5>
												<p class="mg-bottom-8">Some important pages.</p>
												<li class="footer-list li-type-none dp-inline-block mg-right-10 mg-lc-right" v-for="route in target">
													<router-link class="fc-2" :to="{ path: route.path }">
														{{ route.slot }}
													</router-link>
												</li>
											</div>
											<div class="footer-group pd-14">
												<h5 class="footer-title mg-bottom-6">Follow us</h5>
												<p class="mg-bottom-8">Stay connected with us.</p>
												<li class="footer-list li-type-none dp-inline-block mg-right-10 mg-lc-right" v-for="social in medsos">
													<a :href="social.link" target="_blank" rel="noopener noreferrer">
														<i :class="[ 'footer-link', 'ic-2', social.icon ]"></i>
													</a>
												</li>
											</div>
										</div>
										<div class="footer-single flex flex-left pd-14 fc-1">
											&copy hxAri {{ date }}
										</div>
									</div>
								</div>
							</div>
						`
					});
					
					// Install the object instance as a plugin.
					$App.use( $Router );
					
					// Mount element.
					$App.mount( $Root );
				});
				
				return({});
			}));
			
		</script>
	</body>
</html>