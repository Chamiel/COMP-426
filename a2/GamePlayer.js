var GamePlayer = function (name, ui_div) {

	var match = null;
    var position = null;
    var current_game = null;
    var player_key = null;
	var played = document.getElementById("played");
	var hand = document.getElementById('hand');
	var select_card = document.getElementById("select_card");
	var passing = new Set();
	
	var game_log = $("<div class='game_event_message_log'></div>");
	$(ui_div).append(game_log);
	
	
	this.setupMatch = function (hearts_match, pos) {
		match = hearts_match;
		position = pos;
    }
    
    this.getName = function () {
		return name;
    }
	
	this.setupNextGame = function (next_game, pkey) {
		current_game = next_game;
		player_key = pkey;
		current_game.registerEventHandler(Hearts.ALL_EVENTS, function (e) {
			game_log.append($("<div class='text_player_message'>"+e.toString()+"</div>"));
			game_log.scrollTop(game_log.prop("scrollHeight")-game_log.height());
			updateHand();
		});
		current_game.registerEventHandler(Hearts.CARD_PLAYED_EVENT, cardPlayedHandler);
		current_game.registerEventHandler(Hearts.TRICK_COMPLETE_EVENT, trickCompleteHandler);
		current_game.registerEventHandler(Hearts.GAME_OVER_EVENT, updateScore);
	}
	
	var playCard = function(card_to_play) {
		if (!current_game.playCard(card_to_play, player_key)) {
			console.log("error playing card");
		}
	}
	
	var cardPlayedHandler = function(e) {
		var card = e.getCard();
		var pos = e.getPosition();
		var img = $(document.getElementById(pos));
		img.attr('src', getImageString(card));
		img.show();
	}
	
	var trickCompleteHandler = function(e) {
		$(document.getElementById("North")).hide();
		$(document.getElementById("East")).hide();
		$(document.getElementById("South")).hide();
		$(document.getElementById("West")).hide();
	}
	
	var updateScore = function(e) {
		var points1 = current_game.getScore("North");
		var points2 = current_game.getScore("East");
		var points3 = current_game.getScore("South");
		var points4 = current_game.getScore("West");
		$(document.getElementById("score1")).text("NORTH SCORE: "+points1);
		$(document.getElementById("score2")).text("EAST SCORE: "+points2);
		$(document.getElementById("score3")).text("SOUTH SCORE: "+points3);
		$(document.getElementById("score4")).text("WEST SCORE: "+points4);
	}
	
	var getImageString = function (card) {
		var string = window.location.pathname;
		var str = string.substring(0, string.lastIndexOf("a2"))+"a2/cards/";
		var rank = card.getRank();
		switch (rank) {
			case 10: rank = "t"; break;
			case 11: rank = "j"; break;
			case 12: rank = "q"; break;
			case 13: rank = "k"; break;
			case 14: rank = "a"; break;
			default: rank = card.getRank().toString();
		}
		var suit = card.getSuit();
		switch (suit) {
			case 0: suit = "h"; break;
			case 1: suit = "s"; break;
			case 2: suit = "d"; break;
			case 3: suit = "c"; break;
		}
		return str+rank+suit+".gif";
	}
	
	var updateHand = function () {
		hand.innerHTML = "";
		var cards;
		cards = current_game.getHand(player_key).getUnplayedCards(player_key);
		var cards2 = new Set(current_game.getHand(player_key).getPlayableCards(player_key));
		cards.forEach(function(listItem, index) {
			var img = $('<img>').attr('src', getImageString(listItem)).appendTo($(hand)).on("click", function(){
				cardSelected(listItem, this);
			});
			if (cards2.has(listItem))
				$(img).toggleClass("playable");
		});
	}
	
	var cardSelected = function(card, img) {
		if (current_game.getStatus() == 1) {
			if (passing.has(card)) {
				passing.delete(card);
			}
			else
				passing.add(card);
			$(img).toggleClass("passing");
			if (passing.size == 3) {
				if (!current_game.passCards([...passing], player_key)) {
				}
				passing.clear();
				updateHand();
			}
		} else {
			playCard(card, player_key);
			updateHand();
		}
	}

}