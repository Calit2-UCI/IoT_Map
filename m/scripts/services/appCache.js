'use strict';

angular.module('datingMobile').service('appCache', function($angularCacheFactory) {

	var self = this;
	self.user = $angularCacheFactory('user', {
		// This cache can hold 1000 items
		capacity: 1000,
		// Items added to this cache expire after 15 minutes
		maxAge: 900000,
		// Items will be actively deleted when they expire
		deleteOnExpire: 'aggressive',
		// This cache will check for expired items every minute
		recycleFreq: 60000,
		// This cache will clear itself every hour
		cacheFlushInterval: 3600000,
		// This cache will sync itself with localStorage
		storageMode: 'localStorage',
		// Custom implementation of localStorage
		// Full synchronization with localStorage on every operation
		verifyIntegrity: true
		// This callback is executed when the item specified by "key" expires.
		// At this point you could retrieve a fresh value for "key"
		// from the server and re-insert it into the cache.
		// onExpire: function(key, value) {
		// }
	});
	self.form = $angularCacheFactory('form', {
		capacity: 10,
		maxAge: 900000,
		deleteOnExpire: 'aggressive',
		recycleFreq: 60000,
		cacheFlushInterval: 3600000,
		storageMode: 'localStorage',
		verifyIntegrity: true
	});
});