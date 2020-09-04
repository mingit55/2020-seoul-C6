class App {
    constructor(){
        this.$form = $("#sign-up");
        this.canSubmit = false;
        this.setEvents();
    }

    get user_email(){
        return $("#user_email").val();
    }
    get user_name(){
        return $("#user_name").val();
    }
    get password(){
        return $("#password").val();
    }
    get passconf(){
        return $("#passconf").val();
    }
    get image(){
        let input = $("#image")[0];
        return input.files.length > 0 ? input.files[0] : null;
    }

    check(selector, cond, msg){
        let $target = $(selector);
        let $error = $target.siblings(".error");

        if(cond == false){
            $error.text(msg);
            this.canSubmit = false;
        } else {
            $error.text('');
        }
    }

    checkAll(selector, conds, msgs){
        let $target = $(selector);
        let $error = $target.siblings(".error");

        for(let i in conds){
            let cond = conds[i];
            let msg = msgs[i];

            if(cond == false){
                $error.text(msg);
                this.canSubmit = false;
                return;
            }
        }

        $error.text('');
    }

    setEvents(){
        this.$form.on("submit", async e => {
            e.preventDefault();
            this.canSubmit = true;

            let exist = await ( fetch("/api/users/" + this.user_email).then(res => res.json()) );

            this.checkAll(
                "#user_email",
                [
                    /^([a-zA-Z0-9]+)@([a-zA-Z0-9]+)\.([a-zA-Z0-9]{2,4})$/.test(this.user_email),
                    !exist
                ],
                [
                    "올바른 이메일을 입력하세요.",
                    "이미 사용 중인 이메일입니다."
                ]
            );

            this.check(
                "#password",
                /^(?=.*[a-z].*)(?=.*[A-Z].*)(?=.*[0-9].*)(?=.*[!@#$%^&*()].*)([a-zA-Z0-9!@#$%^&*()]{8,})$/.test(this.password),
                "올바른 비밀번호을 입력하세요."
            );

            this.check(
                "#passconf",
                this.password == this.passconf,
                "비밀번호와 비밀번호 확인이 불일치합니다."
            );

            this.check(
                "#user_name",
                /^([ㄱ-ㅎㅏ-ㅣ가-힣]{2,4})$/.test(this.user_name),
                "올바른 이름을 입력하세요."
            );

            console.log(this.image && ["jpg", "gif", "png"].includes(this.image.name.substr(-3).toLowerCase()),
            this.image && this.image.size < 1024 * 1024 * 5);
            this.checkAll(
                "#image",
                [
                    this.image && ["jpg", "gif", "png"].includes(this.image.name.substr(-3).toLowerCase()),
                    this.image && this.image.size < 1024 * 1024 * 5
                ], 
                [
                    "이미지 파일만 업로드 할 수 있습니다.",
                    "이미지 파일은 5MB 이상 업로드 할 수 없습니다."
                ]
            );

            if(this.canSubmit){
                this.$form[0].submit();
                // alert("정상적으로 회원가입 되었습니다.");
            }
                
            
        });
    }   
}

$(function(){
    let app = new App();
});