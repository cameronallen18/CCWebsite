//Functions for edit.general.php

$(document).ready(function() {
            $('#mid').keyup(function() {
                var len = this.value.length;
                if (len >= 2) {
                    this.value = this.value.substring(0, 2);
                }
                $('#midl').text(2 - len);
            });

            $('#suf1').keyup(function() {
                var len = this.value.length;
                if (len >= 4) {
                    this.value = this.value.substring(0, 4);
                }
                $('#suf1l').text(4 - len);
            });
			
			$('#suf2').keyup(function() {
                var len = this.value.length;
                if (len >= 4) {
                    this.value = this.value.substring(0, 4);
                }
                $('#suf2l').text(4 - len);
            });

            $('#avt').keyup(function() {
                var len = this.value.length;
                if (len >= 250) {
                    this.value = this.value.substring(0, 250);
                }
                $('#avtl').text(250 - len);
            });
			
			$('#ta1').keyup(function() {
                var len = this.value.length;
                if (len >= 65) {
                    this.value = this.value.substring(0, 65);
                }
                $('#talength1').text(65 - len);
            });
			
			$('#ta2').keyup(function() {
                var len = this.value.length;
                if (len >= 65) {
                    this.value = this.value.substring(0, 65);
                }
                $('#talength2').text(65 - len);
            });
			
			$('#ta3').keyup(function() {
                var len = this.value.length;
                if (len >= 65) {
                    this.value = this.value.substring(0, 65);
                }
                $('#talength3').text(65 - len);
            });
			
			$('#ta4').keyup(function() {
                var len = this.value.length;
                if (len >= 65) {
                    this.value = this.value.substring(0, 65);
                }
                $('#talength4').text(65 - len);
            });
			
			$('#ta5').keyup(function() {
                var len = this.value.length;
                if (len >= 65) {
                    this.value = this.value.substring(0, 65);
                }
                $('#talength5').text(65 - len);
            });
			
			$('#zpcd1').keyup(function() {
                var len = this.value.length;
                if (len >= 5) {
                    this.value = this.value.substring(0, 5);
                }
                $('#zpcd1l').text(5 - len);
            });
			
			$('#zpcd2').keyup(function() {
                var len = this.value.length;
                if (len >= 5) {
                    this.value = this.value.substring(0, 5);
                }
                $('#zpcd2l').text(5 - len);
            });
			
			$('#wbst').keyup(function() {
                var len = this.value.length;
                if (len >= 120) {
                    this.value = this.value.substring(0, 120);
                }
                $('#wbstl').text(120 - len);
            });
			
			$('#cll1').keyup(function() {
                var len = this.value.length;
                if (len >= 3) {
                    this.value = this.value.substring(0, 3);
                }
                $('#cll1l').text(3 - len);
            });
			
			$('#cll2').keyup(function() {
                var len = this.value.length;
                if (len >= 3) {
                    this.value = this.value.substring(0, 3);
                }
                $('#cll12').text(3 - len);
            });
			
			$('#cll3').keyup(function() {
                var len = this.value.length;
                if (len >= 4) {
                    this.value = this.value.substring(0, 4);
                }
                $('#cll3l').text(4 - len);
            });
			
			$('#a1').keyup(function() {
                var len = this.value.length;
                if (len >= 500) {
                    this.value = this.value.substring(0, 500);
                }
                $('#a1l').text(500 - len);
            });
			
			$('#a2').keyup(function() {
                var len = this.value.length;
                if (len >= 500) {
                    this.value = this.value.substring(0, 500);
                }
                $('#a2l').text(500 - len);
            });
			
			$('#a3').keyup(function() {
                var len = this.value.length;
                if (len >= 500) {
                    this.value = this.value.substring(0, 500);
                }
                $('#a3l').text(500 - len);
            });
			
			$('#bio').keyup(function() {
                var len = this.value.length;
                if (len >= 1500) {
                    this.value = this.value.substring(0, 1500);
                }
                $('#biol').text(1500 - len);
            });
        });