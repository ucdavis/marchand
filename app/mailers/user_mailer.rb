class UserMailer < ApplicationMailer
    # Subject can be set in your I18n file at config/locales/en.yml
    # with the following lookup:
    #
    #   en.lesson_mailer.lesson_request.subject
    #
    def lesson_request(lesson, customer_email)
      @lesson = lesson
      @customer_email = customer_email

      mail to: "historyproject@ucdavis.edu",
        subject: "New Lesson Request from Marchand"
    end

    def image_request(image, customer_email)
      @image = image
      @customer_email = customer_email

      mail to: "historyproject@ucdavis.edu",
        subject: "New Image Request from Marchand"
    end
end
