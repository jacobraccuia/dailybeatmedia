

function renderWaveform(data,plain,clear){
  if(data){
    for(var i = 0; i < data.length; i++){

      plain = plain || 2;
      clear = clear || 1;
      var step = plain+clear;
      if (i % step == 0){
        var sum=0;
        for (var j = 0; j < plain; j++) {
          sum += data[i+j];
        };
        var average = (sum/plain);
        for (var j = 0; j < plain; j++) {
          data[i+j]=average;
        };
        for (var j = plain; j < step; j++) {
          data[i+j]=0;
        };
      }
    } 
    return data;
  }
  else
    return;
}


(function() {
  var JSONP, Waveform,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

  window.Waveform = Waveform = (function() {

    Waveform.name = 'Waveform';

    function Waveform(options) {
      this.redraw = __bind(this.redraw, this);
      this.container = options.container;
      this.canvas = options.canvas;
      this.data = options.data || [];
      this.outerColor = options.outerColor || "transparent";
      this.innerColor = options.innerColor || "#000000";
      this.interpolate = true;
      if (options.interpolate === false) {
        this.interpolate = false;
      }

      if (this.canvas == null) {
        if (this.container) {
          this.canvas = this.createCanvas(this.container, options.width || this.container.clientWidth, options.height || this.container.clientHeight);
        } else {
          throw "Either canvas or container option must be passed";
        }
      }
      this.patchCanvasForIE(this.canvas);
      this.context = this.canvas.getContext("2d");
      this.width = parseInt(this.context.canvas.width, 10);
      this.height = parseInt(this.context.canvas.height, 10);
      if (options.data) {
        this.update(options);
      }
    }

    Waveform.prototype.setData = function(data) {
      return this.data = data;
    };

    Waveform.prototype.setDataInterpolated = function(data) {
      return this.setData(this.interpolateArray(data, this.width));
    };

    Waveform.prototype.setDataCropped = function(data) {
      return this.setData(this.expandArray(data, this.width));
    };

    Waveform.prototype.update = function(options) {
      if (options.interpolate != null) {
        this.interpolate = options.interpolate;
      }
      if (this.interpolate === false) {
        this.setDataCropped(options.data);
      } else {
        this.setDataInterpolated(options.data);
      }
      return this.redraw();
    };

    Waveform.prototype.redraw = function() {
      var d, i, middle, t, _i, _len, _ref, _results, col;
      this.clear();
      if (typeof this.innerColor === "function") {
        this.context.fillStyle = this.innerColor();
        col = this.innerColor();
      } else {
        this.context.fillStyle = this.innerColor;
        col = this.innerColor;
      }

      this.context.fillStyle = 'rgba(255,255,255, .3)';
      
      middle = this.height / 2;
      i = 0;
      _ref = this.data;
      _results = [];

      for (_i = 0, _len = _ref.length; _i < _len; _i++) {
        d = _ref[_i];
        t = this.width / this.data.length;
        if(this.innerColor !== null && typeof this.innerColor === "function" && this.innerColor(i / this.width, d) !== null) {
         //this.context.fillStyle = this.innerColor(i / this.width, d);

          // if gray, fill in, else create gradient
          if(this.innerColor(i / this.width, d) == 'unplayed' || 
            (this.innerColor(i / this.width, d).substring(0, 3) != 'rgb' && this.innerColor(i / this.width, d).substring(0, 1) != '#')
            ) {
            this.context.fillStyle = 'rgba(255, 255, 255, 0.3)';
        } else {

          var grd = this.context.createLinearGradient(0, 0, this.width, 0);
          grd.addColorStop(0,'rgba(0, 226, 207, .8)');
          grd.addColorStop(1, this.innerColor(i / this.width, d));

          this.context.fillStyle = grd;
        }

      }

      this.context.clearRect(t * i, middle - middle * d, t, middle * d * 2);
      this.context.fillRect(t * i, middle - middle * d, t, middle * d * 2);
      _results.push(i++);
    }



    return _results;
  };

  Waveform.prototype.clear = function() {
    this.context.fillStyle = this.outerColor;
    this.context.clearRect(0, 0, this.width, this.height);
    return this.context.fillRect(0, 0, this.width, this.height);
  };

  Waveform.prototype.patchCanvasForIE = function(canvas) {
    var oldGetContext;
    if (typeof window.G_vmlCanvasManager !== "undefined") {
      canvas = window.G_vmlCanvasManager.initElement(canvas);
      oldGetContext = canvas.getContext;
      return canvas.getContext = function(a) {
        var ctx;
        ctx = oldGetContext.apply(canvas, arguments);
        canvas.getContext = oldGetContext;
        return ctx;
      };
    }
  };

  Waveform.prototype.createCanvas = function(container, width, height) {
    var canvas;
    canvas = document.createElement("canvas");
    container.appendChild(canvas);
    canvas.width = width;
    canvas.height = height;
    return canvas;
  };

  Waveform.prototype.expandArray = function(data, limit, defaultValue) {
    var i, newData, _i, _ref;
    if (defaultValue == null) {
      defaultValue = 0.0;
    }
    newData = [];
    if (data.length > limit) {
      newData = data.slice(data.length - limit, data.length);
    } else {
      for (i = _i = 0, _ref = limit - 1; 0 <= _ref ? _i <= _ref : _i >= _ref; i = 0 <= _ref ? ++_i : --_i) {
        newData[i] = data[i] || defaultValue;
      }
    }
    return newData;
  };

  Waveform.prototype.linearInterpolate = function(before, after, atPoint) {
    return before + (after - before) * atPoint;
  };

  Waveform.prototype.interpolateArray = function(data, fitCount) {
    var after, atPoint, before, i, newData, springFactor, tmp;
    newData = new Array();
    springFactor = new Number((data.length - 1) / (fitCount - 1));
    newData[0] = data[0];
    i = 1;
    while (i < fitCount - 1) {
      tmp = i * springFactor;
      before = new Number(Math.floor(tmp)).toFixed();
      after = new Number(Math.ceil(tmp)).toFixed();
      atPoint = tmp - before;
      newData[i] = this.linearInterpolate(data[before], data[after], atPoint);
      i++;
    }
    newData[fitCount - 1] = data[data.length - 1];

      //newData = renderWaveform(newData);

      return newData;
    };

    Waveform.prototype.optionsForSyncedStream = function(options, position, length) {
      var innerColorWasSet, that;
      var position = position;
      var length = length;
      if (options == null) {
        options = {};
      }
      innerColorWasSet = false;
      that = this;
      return {
        whileplaying: this.redraw,
        whileloading: function() {
          var stream;
          if (!innerColorWasSet) {
            stream = this;
            that.innerColor = function(x, y) {
              if (x < position / length) {
                return options.playedColor || "rgba(255,  102, 0, 0.8)";
              } else if (x < stream.bytesLoaded / stream.bytesTotal) {
                return options.loadedColor || "rgba(0, 0, 0, 0.8)";
              } else {
                return options.defaultColor || "rgba(0, 0, 0, 0.4)";
              }
            };
            innerColorWasSet = true;
          }
          return this.redraw;
        }
      };
    };


    Waveform.prototype.dataFromSoundCloudTrack = function(track) {
      var _this = this;

      if(track.waveform_url == undefined) { return false; }

      if(!window.sc_data_cache) { window.sc_data_cache = []; }
      // check cache for waveforms
      if(window.sc_data_cache && window.sc_data_cache[track.waveform_url]) {
        window.sc_data = waveform_data = window.sc_data_cache[track.waveform_url];

        return _this.update({
          data: waveform_data
        });

      }

      return JSONP.get("http://www.waveformjs.org/w", {
        url: track.waveform_url.replace('https', 'http')
      }, function(data) {
        // cache waveform
        window.sc_data_cache[track.waveform_url] = data;
        window.sc_data = data;

        return _this.update({
          data: data
        });
      });
    };

    return Waveform;

  })();
  

 JSONP = (function() {
  var config, counter, encode, head, jsonp, key, load, query, setDefaults, window;
  load = function(url) {
    var done, head, script;
    script = document.createElement("script");
    done = false;
    script.src = url;
    script.async = true;
    script.onload = script.onreadystatechange = function() {
      if (!done && (!this.readyState || this.readyState === "loaded" || this.readyState === "complete")) {
        done = true;
        script.onload = script.onreadystatechange = null;
        if (script && script.parentNode) {
          return script.parentNode.removeChild(script);
        }
      }
    };
    if (!head) {
      head = document.getElementsByTagName("head")[0];
    }
    return head.appendChild(script);
  };
  encode = function(str) {
    return encodeURIComponent(str);
  };
  jsonp = function(url, params, callback, callbackName) {
    var key, query, uniqueName;
    query = ((url || "").indexOf("?") === -1 ? "?" : "&");
    callbackName = callbackName || config["callbackName"] || "callback";
    uniqueName = callbackName + "_json" + (++counter);
    params = params || {};
    for (key in params) {
      if (params.hasOwnProperty(key)) {
        query += encode(key) + "=" + encode(params[key]) + "&";
      }
    }
    window[uniqueName] = function(data) {
      callback(data);
      try {
        delete window[uniqueName];
      } catch (_error) {}
      return window[uniqueName] = null;
    };
    load(url + query + callbackName + "=" + uniqueName);
    return uniqueName;
  };
  setDefaults = function(obj) {
    var config;
    return config = obj;
  };
  counter = 0;
  head = void 0;
  query = void 0;
  key = void 0;
  window = this;
  config = {};
  return {
    get: jsonp,
    init: setDefaults
  };
})();

}).call(this);