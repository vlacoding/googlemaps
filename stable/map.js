      var customLabel = {
        1: {
          label: 'A'
        },
        2: {
          label: 'B'
        },
        3: {
          label: 'C'
        },
        4: {
          label: 'D'
        },
        5: {
          label: 'E'
        }
      };

        function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: new google.maps.LatLng(58.54245,50.02106), // Центр карты
          zoom: 13
        });


          // Путь к php файлу, генерирующему xml со списком меток из БД
           downloadUrl('get.php', function(data) {
            var xml = data.responseXML;
            var markers = xml.documentElement.getElementsByTagName('marker');
            Array.prototype.forEach.call(markers, function(markerElem) {
              var name = markerElem.getAttribute('title');
              var mapdesc = markerElem.getAttribute('mapdesc');
              var slug = markerElem.getAttribute('slug');
              var type = markerElem.getAttribute('category_id');
              var point = new google.maps.LatLng(
                  parseFloat(markerElem.getAttribute('lat')),
                  parseFloat(markerElem.getAttribute('lng')));
                  
    var infoWindow = new google.maps.InfoWindow ({
        content: mapdesc
    });

              // Заголовок метки
              var infowincontent = document.createElement('div');
              var strong = document.createElement('strong');
              strong.textContent = name
              infowincontent.appendChild(strong);
              infowincontent.appendChild(document.createElement('br'));
              
              // Описание метки
              var text = document.createElement('text');
              text.textContent = mapdesc
              infowincontent.appendChild(text);
              infowincontent.appendChild(document.createElement('br'));
              infowincontent.appendChild(document.createElement('br'));
              
              // Ссылка на страницу
              var url = document.createElement('a');
              url.textContent = 'Подробнее'
              var slug2 = '/places/'+slug+'.html';
              url.setAttribute('href',slug2);
              url.setAttribute('target','_blank');
              infowincontent.appendChild(url);
              
              // Маркер
              var icon = customLabel[type] || {};
              
              var marker = new google.maps.Marker({
                map: map,
                position: point,
                label: icon.label
              });
              marker.addListener('click', function() {
                infoWindow.setContent(infowincontent);
                infoWindow.open(map, marker);
              });
            });
          });
        }



      function downloadUrl(url, callback) {
        var request = window.ActiveXObject ?
            new ActiveXObject('Microsoft.XMLHTTP') :
            new XMLHttpRequest;

        request.onreadystatechange = function() {
          if (request.readyState == 4) {
            request.onreadystatechange = doNothing;
            callback(request, request.status);
          }
        };

        request.open('GET', url, true);
        request.send(null);
      }

      function doNothing() {}
