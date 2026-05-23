<?php
    session_start();

    if(!isset($_SESSION["user_id"])){
        header("Location: login.php");
    }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>URides</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <meta content="Free HTML Templates" name="keywords" />
    <meta content="Free HTML Templates" name="description" />

    <!-- Profile Dropdown Menu -->
    <link href="css/profileStyle.css" rel="stylesheet" />
    <script src="js/script.js" defer></script>
    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon" />

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" />
    <link
      href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Rubik&display=swap"
      rel="stylesheet"
    />

    <!-- Font Awesome -->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css"
      rel="stylesheet"
    />

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link
      href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css"
      rel="stylesheet"
    />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet" />

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet" />

    <script src="https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.js"></script>
    <link
      href="https://api.tiles.mapbox.com/mapbox-gl-js/v0.26.0/mapbox-gl.css"
      rel="stylesheet"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fetch/1.0.0/fetch.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/2.0.3/timeago.min.js"></script>
    <style>
      body, html {
        height: 100%;
        margin: 0;
        font-family: Arial, sans-serif;
        background-color: #ffffff;
      }

      /* Flexbox to center the map and text */
      .content-container {
        display: flex;
        flex-direction: column;  
        justify-content: center; 
        align-items: center;     
        height: 100vh;           
      }

      .live-location-text {
        font-size: 48px;
        font-family: 'Oswald', sans-serif; /* Modern, stylish font */
        font-weight: 700; /* Bold text */
        color: #2c3e50; /* Dark, elegant color */
        text-transform: uppercase; /* All uppercase letters */
        letter-spacing: 2px; /* Add spacing between letters */
        text-align: center; /* Center the text */
        margin-bottom: 30px; /* Space between text and map */
        text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.2); /* More pronounced shadow */
    }

      /* Map container */
      #map-container {
        width: 70%;
        height: 80%;
        background-color: #ffffff;
      }

      #map {
        height: 100%;
        width: 100%;
        border-radius: 8px;
      }

      @media (max-width: 768px) {
        #map-container {
          width: 90%;
          height: 50vh;
        }

        .live-location-text {
          font-size: 32px; /* Adjust font size for smaller screens */
          letter-spacing: 1px; /* Adjust spacing for readability */
          margin-bottom: 20px; /* Adjust spacing */
        }
      }
    </style>

  </head>

  <body>
    <!-- Topbar Start -->
    <?php
        include 'topbar.php';
    ?>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid position-relative nav-bar p-0">
      <div class="position-relative px-lg-5" style="z-index: 9">
        <nav
          class="navbar navbar-expand-lg bg-secondary navbar-dark py-3 py-lg-0 pl-3 pl-lg-5"
        >
          <a href="home.php" class="navbar-brand">
            <h1 class="text-uppercase text-primary mb-1">URides</h1>
          </a>
          <button
            type="button"
            class="navbar-toggler"
            data-toggle="collapse"
            data-target="#navbarCollapse"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div
            class="collapse navbar-collapse justify-content-between px-3"
            id="navbarCollapse"
          >
            <div class="navbar-nav ml-auto py-0">
              <a href="home.php" class="nav-item nav-link">Home</a>
              <a href="about.php" class="nav-item nav-link">About</a>
              <a href="chat_inbox.php" class="nav-item nav-link">Messages</a>
              <a href="requests.php" class="nav-item nav-link">Requests</a>
              <div class="nav-item dropdown">
                <a
                  href="#"
                  class="nav-link active dropdown-toggle"
                  data-toggle="dropdown"
                  >Services</a
                >
                <div class="dropdown-menu rounded-0 m-0">
                  <a href="rideShare.php" class="dropdown-item"
                    >Request a Ride</a
                  >
                  <a href="shuttle.php" class="dropdown-item">Shuttle</a>
                  <a href="booking.php" class="dropdown-item">Cycle Rental</a>
                </div>
              </div>
              <a href="contact.php" class="nav-item nav-link">Contact</a>

              <!-- <a href="logout.php" class="nav-item nav-link">Logout</a> -->

              <!-- Profile Dropdown Menu -->
              <?php
                            include 'profileDropdown.php';
                        ?>
              <!-- End Profile Dropdown Menu -->
            </div>
          </div>
        </nav>
      </div>
    </div>
    <!-- Navbar End -->

    
    <!-- Page Header Start -->
    <div class="container-fluid page-header">
      <h1 class="display-3 text-uppercase text-white mb-3">Shuttle Service</h1>
      <div class="d-inline-flex text-white">
        <h6 class="text-uppercase m-0">
          <a class="text-white" href="home.php">Home</a>
        </h6>
        <h6 class="text-body m-0 px-3">/</h6>
        <h6 class="text-uppercase text-body m-0">Shuttle Service</h6>
      </div>
    </div>
    <!-- Page Header Start -->

    <!-- Content Section -->
    <div class="content-container">
      <div class="live-location-text">Live Locations</div>
      
      <!-- Map Section -->
      <div id="map-container">
        <div id="map"></div>
      </div>
    </div>

    <script>
      var MARKER_ICON = "img/bus.png";
      // THINGSPEAK_CHANNEL_IDS is used as identifier for Individual Bus
      var THINGSPEAK_CHANNEL_IDS = [2672645]; // Add your ThingSpeak channel IDs here
      var NUMBER_OF_POINTS = 200;
      var MAPBOX_TOKEN = "<?php echo getenv('MAPBOX_ACCESS_TOKEN') ?: ''; ?>";

      mapboxgl.accessToken = MAPBOX_TOKEN;

      var map = new mapboxgl.Map({
        container: "map",
        style: "mapbox://styles/mapbox/streets-v9",
        center: [90.425638, 23.780569], // Initial center of the map
        zoom: 13, // Initial zoom level
      });

      map.addControl(new mapboxgl.NavigationControl());

      var currentMarker = null; // Store the current marker

      function fetchData() {
        const tenMinutesago = new Date(
          Date.now() - 0.5 * 60 * 1000
        ).toISOString(); // 1 hour ago

        const fetchPromises = THINGSPEAK_CHANNEL_IDS.map(function (channelId) {
          return fetch(
            "https://api.thingspeak.com/channels/" +
              channelId +
              "/feeds.json?results=" +
              NUMBER_OF_POINTS
          )
            .then(function (response) {
              if (!response.ok) {
                throw new Error("Network response was not ok");
              }
              return response.json();
            })
            .then(function (json) {
              return json.feeds
                .filter(function (point) {
                  return new Date(point.created_at) >= new Date(tenMinutesago);
                })
                .map(function (point) {
                  return {
                    coords: [
                      parseFloat(point.field3),
                      parseFloat(point.field2),
                    ],
                    time: point.created_at,
                    channel: channelId,
                  };
                });
            })
            .catch(function (error) {
              console.error(
                "Error fetching data from channel " + channelId + ":",
                error
              );
              return []; // Return an empty array in case of error
            });
        });

        Promise.all(fetchPromises)
          .then(function (results) {
            const allPoints = results.flat();
            draw(allPoints);
            console.log("Data processed successfully!");
          })
          .catch(function (error) {
            console.error("Error processing data:", error);
            console.log(
              "Error processing data from ThingSpeak. Please try again later."
            );
          });
      }

      function draw(points) {
        if (points.length === 0) {
          console.log("No data points in the past hour.");
          return;
        }

        // Group points by channel
        var pointsByChannel = points.reduce(function (acc, point) {
          if (!acc[point.channel]) {
            acc[point.channel] = [];
          }
          acc[point.channel].push(point);
          return acc;
        }, {});

        // Iterate over each channel
        Object.keys(pointsByChannel).forEach(function (channel) {
          var channelPoints = pointsByChannel[channel];
          var latestPoint = channelPoints[channelPoints.length - 1];

          console.log("Latest point for channel " + channel + ":", latestPoint);

          var text =
            new timeago().format(latestPoint.time) +
            "<br />" +
            latestPoint.coords[0] +
            ", " +
            latestPoint.coords[1];
          var popup = new mapboxgl.Popup({
            offset: [0, -30],
          }).setHTML(text);

          var el = document.createElement("div");
          el.id = "marker";
          el.style.backgroundImage = "url(" + MARKER_ICON + ")";
          el.style.width = "50px"; // Adjust the size as needed
          el.style.height = "50px"; // Adjust the size as needed
          el.style.backgroundSize = "cover"; // Ensure the image is scaled correctly

          // Remove the previous marker if it exists
          if (currentMarker) {
            currentMarker.remove();
          }

          // Add the new marker and store it in currentMarker
          currentMarker = new mapboxgl.Marker(el, {
            offset: [-25, -25],
          })
            .setLngLat(latestPoint.coords)
            .setPopup(popup)
            .addTo(map);
        });
      }

      // Initial fetch
      fetchData();

      // Set interval to fetch data every 6 seconds
      setInterval(fetchData, 10);
    </script>


    <!-- Footer Start -->
    <?php
  include 'footer.php';
  ?>
    <!-- Footer End -->

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"
      ><i class="fa fa-angle-double-up"></i
    ></a>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
  </body>
</html>
<script type="text/javascript" src="js/script.js"></script>
